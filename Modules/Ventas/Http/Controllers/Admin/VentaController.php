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
        if($request->has('credito')){
            $credito = true;
        }
        $today = Carbon::now()->format('d/m/Y');
        $tipos_factura = ['todos' => '--'];
        $tipos_factura = array_merge($tipos_factura, Venta::$tipos_factura);
        return view('ventas::admin.ventas.index', compact('today', 'credito', 'tipos_factura'));
    }


    public function index_ajax(Request $re)
    {
        $query = $this->query_index_ajax($re);
        $ventas = $query->get();
        $suma = $ventas->sum('monto_total');
        $suma = number_format($suma, 0, ',', '.');
        $object = Datatables::of($query)
            ->addColumn('acciones', function( $venta ) use ($re){
              $html = '
                <div class="btn-group">';
                $pagado = str_replace(',', '.',str_replace('.', '', $venta->monto_pagado));
                $total = str_replace(',', '.',str_replace('.', '', $venta->monto_total));
                  if($re->has('credito') && $re->credito && $pagado < $total)
                  $html .=
                  '
                    <button class="btn btn-warning btn-flat pagar" venta="'.$venta->id.'">Pagar</button>
                  ';
                  $html .= '
                  <button class="btn btn-default btn-flat btn-show" style="display:table; margin:auto">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                  </button>
                  <button class="btn btn-default btn-flat btn-download" style="display:table; margin:auto">
                    <i class="fa fa-download" aria-hidden="true"></i>
                  </button>
                  <button class="btn btn-default btn-flat btn-print" style="display:table; margin:auto">
                    <i class="fa fa-print" aria-hidden="true"></i>
                  </button>
                </div>';
              return $html;
            })
            ->with([
              'suma' => $suma
            ])
            ->editColumn('created_at', function( $venta ){
              return $venta->created_at->format('d/m/y');
            })
            ->rawColumns(['acciones'])
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

        if($re->has('credito') && $re->credito)
          $query->where('tipo_factura', 'credito');

        if($re->has('tipo_factura') && $re->tipo_factura != 'todos')
          $query->where('tipo_factura', $re->tipo_factura);
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
        $nro_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->pluck('value')->toArray();
        $nro_factura = implode('-',$nro_factura);
        $tipos_factura = Venta::$tipos_factura;
        $descuentos = (array)json_decode(\Configuracion::where('slug', 'descuentos')->first()->value);
        return view('ventas::admin.ventas.create', compact('nro_factura', 'tipos_factura', 'descuentos'));
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
        if($request->tipo_factura == 'credito' && $request->pago_cliente == null){
          $request->monto_pagado = 0;
        }
        $request = $this->getDatosFacturacion($request);
        if($request->tipo_factura == 'contado')
          $request['monto_pagado'] = $request->monto_total;
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
        if($request->generar_factura){
          $conf_factura = Configuracion::where('slug', 'factura')->orderBy('orden')->get()->last();
          $conf_factura->value = str_pad($conf_factura->value + 1, 7, '0', STR_PAD_LEFT);
          $conf_factura->save();
        }
        DB::commit();
      }catch(\Exception $e){
        return redirect()
            ->back()
            ->withError("Ocurrió un error al crear la venta");
      }
      $query = [];
      if($request->tipo_factura == 'credito')
        $query = ['credito'];

      return redirect()->route('admin.ventas.venta.index', $query)
          ->withSuccess('Venta creado exitosamente');
    }

    private function getDatosFacturacion(Request $request){
      if($request->generar_factura){
        $request ['razon_social'] = $request->datos_razon_social;
        $request['ruc'] = $request->datos_ruc;
        $request['direccion'] = $request->datos_direccion;
        $request['telefono'] = $request->datos_telefono;
      }else{
        $datos = DatosFacturacion::where('ruc', 'xxxxxx')->first();
        if(isset($datos)){
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
        return view('ventas::admin.ventas.edit', compact('venta'));
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
        $this->venta->update($venta, $request->all());

        return redirect()->route('admin.ventas.venta.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('ventas::ventas.title.ventas')]));
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
}
