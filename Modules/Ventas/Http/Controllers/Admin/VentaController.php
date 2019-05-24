<?php

namespace Modules\Ventas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ventas\Entities\Venta;
use Modules\Clientes\Entities\DatosFacturacion;
use Modules\Productos\Entities\Producto;
use Modules\Configuracion\Entities\Configuracion;
use Modules\Ventas\Entities\VentaDetalle;
use Modules\Ventas\Http\Requests\CreateVentaRequest;
use Modules\Ventas\Http\Requests\UpdateVentaRequest;
use Modules\Ventas\Repositories\VentaRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Log;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;
use View;
use Zip;

class VentaController extends AdminBaseController
{
    /**
     * @var VentaRepository
     */
    private $venta;

    public function __construct(VentaRepository $venta)
    {
        parent::__construct();

        $this->venta = $venta;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $credito = false;
        $parcial = false;
        if($request->has('credito')){
            $credito = true;
        }
        elseif($request->has('parcial')){
            $parcial = true;
        }
        $today = Carbon::now()->format('d/m/Y');
        $today = null;
        $tipos_factura = ['todos' => '--'];
        $tipos_factura = array_merge($tipos_factura, Venta::$tipos_factura);
        $con_factura = ['todos' => '--', '1' => 'Sí', '0' => 'No'];
        return view('ventas::admin.ventas.index', compact('parcial', 'today', 'credito', 'tipos_factura', 'con_factura'));
    }


    public function index_ajax(Request $re)
    {
        $query = $this->query_index_ajax($re);
        $ventas = $query->get();
        $suma = $ventas->reduce(function ($carry, $venta){
            if($venta->anulado)
              return $carry;
            return $carry + $venta->monto_total;
        });
        $suma = number_format($suma, 0, ',', '.');
        $object = Datatables::of($query)
            ->addColumn('checkbox', function( $venta ) use ($re) {
              if($re->parcial)
                return '';
              if($venta->generar_factura)
                if($venta->anulado)
                  return '<i title="Anulado" style="color:#a80615;" class="fa fa-times" aria-hidden="true"></i>';
                else
                  return '<input type="checkbox" name=venta_id[] value="'.$venta->id.'"/>';
              else
                return '';
            }, 0)
            ->addColumn('acciones', function( $venta ) use ($re){
              $refacturar_route = route('admin.ventas.venta.edit', $venta->id);
              $actualizar_route = route('admin.ventas.venta.actualizar', $venta->id);
              $reimpimir_route = route('admin.ventas.venta.exportar', ['venta_id' => $venta->id, 'download' => false, 'format' => 'pdf']);
              if($venta->anulado)
                return '';
              $html = '
                <div class="btn-group">';
                $pagado = str_replace(',', '.',str_replace('.', '', $venta->monto_pagado));
                $total = str_replace(',', '.',str_replace('.', '', $venta->monto_total));
                  if($re->has('credito') && $re->credito && $pagado < $total)
                  $html .=
                  '
                    <button type="button" class="btn btn-warning btn-flat pagar" venta="'.$venta->id.'">Pagar</button>
                  ';
                  // $html .= '
                  // <button type="button" class="btn btn-default btn-flat btn-show" style="display:table; margin:auto">
                  //   <i title="Ver" class="fa fa-eye" aria-hidden="true"></i>
                  // </button>
                  // ';
                  if($venta->generar_factura  && !$re->parcial)
                    $html .=
                    '<a href="'.$reimpimir_route.'" target="blank_" title="imprimir" class="btn btn-default btn-flat btn-download" style="display:table; margin:auto">
                      <i title="Descargar" class="fa fa-print" aria-hidden="true"></i>
                    </a>';

                  if(!$re->parcial)
                    $html .= '<a title="Refacturar" href="'.$refacturar_route.'" class="btn btn-default btn-flat btn-download" style="display:table; margin:auto">
                      <i class="fa fa-file-text-o" aria-hidden="true"></i>
                    </a>
                </div>';
                  else
                  $html .= '<a title="Actualizar" href="'.$actualizar_route.'" class="btn btn-default btn-flat btn-download" style="display:table; margin:auto">
                      <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                  </div>';
              return $html;
            })
            ->with([
              'suma' => $suma
            ])
            ->editColumn('created_at', function( $venta ){
              return $venta->created_at->format('d/m/y H:i');
            })
            ->editColumn('updated_at', function( $venta ){
              return $venta->updated_at->format('d/m/y H:i');
            })
            ->rawColumns(['checkbox','acciones'])
            ->make(true);
        $data = $object->getData(true);
        return response()->json( $data );
    }

    public function query_index_ajax($re){
        $query = Venta::select();
        if(isset($re->nro_factura) && trim($re->nro_factura) != '')
          $query->where('nro_factura', 'LIKE', '%'.$re->nro_factura.'%');

        if(isset($re->razon_social) && trim($re->razon_social) != '')
          $query->where('razon_social', 'LIKE', '%'.$re->razon_social.'%');

        if (isset($re->fecha_desde) && trim($re->fecha_desde) != '')
          $query->whereDate('created_at', '>=', $this->fechaFormat($re->fecha_desde) );

        if (isset($re->fecha_hasta) && trim($re->fecha_hasta) != '')
          $query->whereDate('created_at', '<=', $this->fechaFormat($re->fecha_hasta) );

        if (isset($re->fecha_update_desde) && trim($re->fecha_update_desde) != '')
          $query->whereDate('updated_at', '>=', $this->fechaFormat($re->fecha_update_desde) );

        if (isset($re->fecha_update_hasta) && trim($re->fecha_update_hasta) != '')
          $query->whereDate('updated_at', '<=', $this->fechaFormat($re->fecha_update_hasta) );

        if($re->has('credito') && $re->credito)
          $query->where('tipo_factura', 'credito');

        if($re->has('parcial') && $re->parcial)
          $query->where('parcial', true);

        if($re->has('tipo_factura') && $re->tipo_factura != 'todos')
          $query->where('tipo_factura', $re->tipo_factura);

        if($re->has('con_factura') && $re->con_factura != 'todos')
          $query->where('generar_factura', $re->con_factura);

        if($re->has('anulado') && $re->anulado != 'todos')
          $query->where('anulado', $re->anulado);

//        $query->orderBy('created_at');
        return $query;
    }

    private function fechaFormat($date){
       return date("Y-m-d", strtotime( str_replace('/', '-', $date)));
    }

    public function detalles(Venta $venta){
      return view('ventas::admin.ventas.detalles', compact('venta'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $parcial = false;
        $nro_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->pluck('value')->toArray();
        $nro_factura = implode('-',$nro_factura);
        $tipos_factura = Venta::$tipos_factura;
        $descuentos = (array)json_decode(Configuracion::where('slug', 'descuentos')->first()->value);
        return view('ventas::admin.ventas.create', compact('parcial','nro_factura', 'tipos_factura', 'descuentos'));
    }

    public function create_parcial()
    {
        $parcial = true;
        $nro_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->pluck('value')->toArray();
        $nro_factura = implode('-',$nro_factura);
        $tipos_factura = Venta::$tipos_factura;
        $descuentos = (array)json_decode(Configuracion::where('slug', 'descuentos')->first()->value);
        return view('ventas::admin.ventas.create', compact('parcial', 'nro_factura', 'tipos_factura', 'descuentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateVentaRequest $request
     * @return Response
     */
    public function store(CreateVentaRequest $request)
    {
      try{
        DB::beginTransaction();
        if($request->tipo_factura == 'credito' && $request->monto_pagado == null){
          $request['monto_pagado'] = 0;
        }
        $request = $this->getDatosFacturacion($request);
        if($request->has('parcial') && $request->parcial){
          $request['tipo_factura'] = null;
          if(isset($request['monto_pagado']) && $request['monto_pagado'] != ''){
            if($request['monto_pagado'] > $request['modal-monto-total'])
              $request['monto_pagado'] = $request['modal-monto-total'];
          }else{
            $request['monto_pagado'] = 0;
          }
          $request['monto_pagado'] = isset($request['modal-monto-total']) ? $request['monto_pagado'] : 0;
        }else{
          $request['parcial'] = false;
          if($request->tipo_factura == 'contado')
            $request['monto_pagado'] = $request->monto_total;
        }
        $venta = $this->venta->create($request->all());
        foreach ($request->producto_id as $key => $producto_id) {
          $detalle = new VentaDetalle();
          $detalle->venta_id = $venta->id;
          $detalle->producto_id = $producto_id;
          $detalle->cantidad = $request->cantidad[$key];
          $detalle->precio_unitario = $request->precio_unitario[$key];
          $detalle->descuento = $request->descuento[$key];
          $detalle->precio_subtotal = $request->subtotal[$key];
          $detalle->save();
          $producto = Producto::find($producto_id);
          $producto->stock -= $request->cantidad[$key];
          $producto->save();
        }
        if($request->has('edit') && $request->edit){
          $factura = Venta::find($request->factura_a_anular);
          $factura->anulado = true;
          foreach ($factura->detalles as $detalle) {
            $producto = $detalle->producto;
            $producto->stock += $detalle->cantidad;
            $producto->save();
          }
          $factura->save();
          $venta->created_at = Carbon::createFromFormat('d/m/Y',$request->fecha);
          $venta->updated_at = Carbon::createFromFormat('d/m/Y',$request->fecha);
          $venta->save();
          if($request->generar_factura){
            $conf_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->last();
            $nro_factura = (int)explode('-',$request->nro_factura)[2];
            if((int)$conf_factura->value == (int)$nro_factura){
              $conf_factura->value = str_pad($conf_factura->value + 1, 7, '0', STR_PAD_LEFT);
              $conf_factura->save();
            }elseif ((int)$conf_factura->value < (int)$nro_factura) {
              $conf_factura->value = str_pad((int)$nro_factura + 1, 7, '0', STR_PAD_LEFT);
              $conf_factura->save();
            }
          }
        }else{
          if(!$request->parcial){
            if($request->generar_factura){
              $conf_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->last();
              $conf_factura->value = str_pad($conf_factura->value + 1, 7, '0', STR_PAD_LEFT);
              $conf_factura->save();
            }
          }
        }
        DB::commit();
      }catch(\Exception $e){
        return response()->json(['error'=> $e]);
      }
      $generar_factura = $request->generar_factura;
      if($request->parcial)
        $generar_factura = false;

      return response()->json(['venta_id'=> $venta->id, 'generar_factura' => $generar_factura, 'parcial' => $request->parcial]);
    }

    private function getDatosFacturacion(Request $request){
      if($request->generar_factura){
        $request ['razon_social'] = $request->datos_razon_social;
        $request['ruc'] = $request->datos_ruc;
        $request['direccion'] = $request->datos_direccion;
        $request['telefono'] = $request->datos_telefono;
      }else{
        $datos = DatosFacturacion::where('ruc', 'xxxxxx')->first();
        if(!isset($datos)){
          $datos = new DatosFacturacion();
          $datos->ruc = 'xxxxxx';
          $datos->razon_social = 'Cliente Mostrador';
          $datos->save();
        }
        $request ['razon_social'] = $datos->razon_social;
        $request['ruc'] = $datos->ruc;
        $request['datos_id'] = $datos->id;
      }
      return $request;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Venta $venta
     * @return Response
     */
    public function edit(Venta $venta)
    {
        $factura = $venta;
        $descuentos = (array)json_decode(Configuracion::where('slug', 'descuentos')->first()->value);
        $edit = 1;
        $nro_factura = $factura->nro_factura;
        $tipos_factura = Venta::$tipos_factura;
        $datos_id = -1;
        if($factura->generar_factura){
          $datos = DatosFacturacion::where('ruc', $factura->ruc)->first();
          if(isset($datos)){
            $datos_id = $datos->id;
          }
        }
        return view('ventas::admin.ventas.edit', compact('datos_id', 'tipos_factura','factura', 'descuentos', 'edit', 'nro_factura'));
    }

    public function actualizar(Venta $venta){
      $descuentos = (array)json_decode(Configuracion::where('slug', 'descuentos')->first()->value);
      $actualizar = 1;
      $nro_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->pluck('value')->toArray();
      $nro_factura = implode('-',$nro_factura);
      $tipos_factura = Venta::$tipos_factura;
      $datos_id = -1;
      if($venta->generar_factura){
        $datos = DatosFacturacion::where('ruc', $venta->ruc)->first();
        if(isset($datos)){
          $datos_id = $datos->id;
        }
      }
      return view('ventas::admin.ventas.actualizar', compact('datos_id', 'tipos_factura','venta', 'descuentos', 'actualizar', 'nro_factura'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  Venta $venta
     * @param  UpdateVentaRequest $request
     * @return Response
     */
    public function update(Venta $venta, UpdateVentaRequest $request)
    {
        try{
          DB::beginTransaction();
          $request = $this->getDatosFacturacion($request);
          if($request->parcial){
            if($request['monto_pagado'] > $request['modal-monto-total'])
              $request['monto_pagado'] = $request->monto_total;
            $request['monto_pagado'] += $venta->monto_pagado;
            $request['tipo_factura'] = null;
            $request['nro_factura'] = 'xxx-xxx-xxxxxx';
          }else{
            if($request->tipo_factura == 'contado')
              $request['monto_pagado'] = $request->monto_total;
          }
          $venta = $this->venta->update($venta, $request->all());
          foreach ($venta->detalles as $detalle) {
            $producto = Producto::find($detalle->producto_id);
            $producto->stock += $detalle->cantidad;
            $producto->save();
            $detalle->delete();
          }
          foreach ($request->producto_id as $key => $producto_id) {
            $detalle = new VentaDetalle();
            $detalle->venta_id = $venta->id;
            $detalle->producto_id = $producto_id;
            $detalle->cantidad = $request->cantidad[$key];
            $detalle->precio_unitario = $request->precio_unitario[$key];
            $detalle->descuento = $request->descuento[$key];
            $detalle->precio_subtotal = $request->subtotal[$key];
            $detalle->save();
            $producto = Producto::find($producto_id);
            $producto->stock -= $request->cantidad[$key];
            $producto->save();
          }
          if($request->generar_factura){
            $conf_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->last();
            $conf_factura->value = str_pad($conf_factura->value + 1, 7, '0', STR_PAD_LEFT);
            $conf_factura->save();
          }
          DB::commit();
        }catch(\Exception $e){
          return response()->json(['error'=> $e]);
        }
        $generar_factura = $request->generar_factura;
        if($request->parcial)
          $generar_factura = false;

        return response()->json(['venta_id'=> $venta->id, 'generar_factura' => $generar_factura, 'parcial' => $request->parcial]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Venta $venta
     * @return Response
     */
    public function destroy(Venta $venta)
    {
        $this->venta->destroy($venta);

        return redirect()->route('admin.ventas.venta.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('ventas::ventas.title.ventas')]));
    }

    public function pago_credito(Request $request){
      try{
        $venta = Venta::find($request->venta_id);
        $venta->monto_pagado = $request->monto_pagado;
        $venta->save();
        return response()->json(['error' => false, 'message' => 'El pago se realizó con éxito']);
      }catch(Exception $e){
        return response()->json(['error' => true, 'message' => 'Ocurrió un error al intentar guardar el pago']);
      }
    }

    public function export_to_pdf(Request $request) {
      $facturaBoxes = json_decode(json_encode([
        'fecha' => ['x' => 3.6, 'y' => 2.5, 'width' => 100],
        'contado' => ['x' => 17.5, 'y' => 2.5, 'width' => 100],
        'credito' => ['x' => 19.8, 'y' => 2.5, 'width' => 100],
        'nombre' => ['x' => 4.2, 'y' => 2.9, 'width' => 100],
        'ruc' => ['x' => 17, 'y' => 2.9, 'width' => 100],
        'direccion' => ['x' => 2.8, 'y' => 3.3, 'width' => 100],
        'telefono' => ['x' => 17.6, 'y' => 3.3, 'width' => 100],
        'vencimiento' => ['x' => 15.3, 'y' => 3.7, 'width' => 100],
        'cantidad' => ['x' => 2, 'y' => 4.7, 'width' => 100],
        'producto' => ['x' => 3.5, 'y' => 4.7, 'width' => 100],
        'precio_unitario' => ['x' => 13, 'y' => 4.7, 'width' => 100],
        'iva' => ['x' => 18.8, 'y' => 4.7, 'width' => 100],
        'subtotal' => ['x' => 18.8, 'y' => 10.2, 'width' => 100],
        'total_letras' => ['x' => 3, 'y' => 10.55, 'width' => 100],
        'total' => ['x' => 17.6, 'y' => 10.55, 'width' => 100],
        'iva_10' => ['x' => 7.2, 'y' => 10.9, 'width' => 100],
        'total_iva' => ['x' => 10.7, 'y' => 10.9, 'width' => 100],
        'duplicado' => 11.75
      ]));
      $venta = Venta::find($request->venta_id);
      $format = $request->format;
      if($request->download ==   "true") {
          $pdf = PDF::loadView('ventas::pdf.factura',compact('venta','facturaBoxes','format'));
          $pdf->setPaper('Legal', 'portrait');
          return $pdf->download('factura-'.$venta->nro_factura.'.pdf');
      }else {
          if($format == "pdf") {
              $pdf = PDF::loadView('ventas::pdf.factura',compact('venta','facturaBoxes','format'));
              //$pdf->setPaper('Legal', 'portrait');
              return $pdf->stream('factura-'.$venta->nro_factura.'.pdf');
          }else {
              $view = View::make('ventas::pdf.factura',compact('venta','facturaBoxes','format'));
              return $view->render();

          }
      }
  }

  public function download_facturas(Request $request) {
    if(!count($request->venta_id))
      return redirect()->back()->withWarning('No se seleccionaron facturas');
    $facturaBoxes = json_decode(json_encode([
      'fecha' => ['x' => 3.6, 'y' => 2.5, 'width' => 100],
      'contado' => ['x' => 17.5, 'y' => 2.5, 'width' => 100],
      'credito' => ['x' => 19.8, 'y' => 2.5, 'width' => 100],
      'nombre' => ['x' => 4.2, 'y' => 2.9, 'width' => 100],
      'ruc' => ['x' => 17, 'y' => 2.9, 'width' => 100],
      'direccion' => ['x' => 2.8, 'y' => 3.3, 'width' => 100],
      'telefono' => ['x' => 17.6, 'y' => 3.3, 'width' => 100],
      'vencimiento' => ['x' => 15.3, 'y' => 3.7, 'width' => 100],
      'cantidad' => ['x' => 2, 'y' => 4.7, 'width' => 100],
      'producto' => ['x' => 3.5, 'y' => 4.7, 'width' => 100],
      'precio_unitario' => ['x' => 13, 'y' => 4.7, 'width' => 100],
      'iva' => ['x' => 18.8, 'y' => 4.7, 'width' => 100],
      'subtotal' => ['x' => 18.8, 'y' => 10.2, 'width' => 100],
      'total_letras' => ['x' => 3, 'y' => 10.55, 'width' => 100],
      'total' => ['x' => 17.6, 'y' => 10.55, 'width' => 100],
      'iva_10' => ['x' => 7.2, 'y' => 10.9, 'width' => 100],
      'total_iva' => ['x' => 10.7, 'y' => 10.9, 'width' => 100],
      'duplicado' => 11.75
    ]));
    $zip_path = public_path().'/facturas/facturas.zip';
    $zip = Zip::create($zip_path);
    $files = [];
    foreach($request->venta_id as $venta_id) {
      $venta = Venta::find($venta_id);
      $format = 'pdf';
      $pdf = PDF::loadView('ventas::pdf.factura',compact('venta','facturaBoxes','format'));
      $file_path = public_path().'/facturas/factura_'.$venta->nro_factura.'.pdf';
      array_push($files, $file_path);
      $pdf->save($file_path);
      $zip->add($file_path);
    }
    $zip->close();
    foreach ($files as $file)
      if(file_exists($file))
        unlink($file);
    return Response()->download($zip_path)->deleteFileAfterSend(true);
  }
}
