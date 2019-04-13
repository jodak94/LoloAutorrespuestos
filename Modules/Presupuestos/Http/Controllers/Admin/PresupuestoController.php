<?php

namespace Modules\Presupuestos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ventas\Entities\Venta;
use Modules\Presupuestos\Entities\Presupuesto;
use Modules\Presupuestos\Entities\PresupuestoDetalle;
use Modules\Productos\Entities\Producto;
use Modules\Presupuestos\Http\Requests\CreatePresupuestoRequest;
use Modules\Presupuestos\Http\Requests\UpdatePresupuestoRequest;
use Modules\Presupuestos\Repositories\PresupuestoRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use DB;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use View;

class PresupuestoController extends AdminBaseController
{
    /**
     * @var PresupuestoRepository
     */
    private $presupuesto;

    public function __construct(PresupuestoRepository $presupuesto)
    {
        parent::__construct();

        $this->presupuesto = $presupuesto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $today = Carbon::now();
        $fecha_desde = $today;
        $fecha_desde->day = 1;
        $fecha_desde = $fecha_desde->format('d/m/Y');
        $today = Carbon::now()->format('d/m/Y');
        return view('presupuestos::admin.presupuestos.index', compact('today', 'fecha_desde'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $nro_presupuesto = Presupuesto::orderBy('created_at', 'desc')->limit(1)->get()[0]->nro_presupuesto;
        $nro_presupuesto = str_pad($nro_presupuesto + 1, 9, '0', STR_PAD_LEFT);
        $descuentos = (array)json_decode(\Configuracion::where('slug', 'descuentos')->first()->value);
        return view('presupuestos::admin.presupuestos.create',compact('nro_presupuesto','descuentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePresupuestoRequest $request
     * @return Response
     */
    public function store(CreatePresupuestoRequest $request)
    {
        try{
            DB::beginTransaction();
            $presupuesto = $this->presupuesto->create($request->all());
            foreach ($request->producto_id as $key => $producto_id) {
              $detalle = new PresupuestoDetalle();
              $detalle->presupuesto_id = $presupuesto->id;
              $detalle->producto_id = $producto_id;
              $detalle->cantidad = $request->cantidad[$key];
              $detalle->descuento = $request->descuento[$key];
              $detalle->precio_unitario = $request->precio_unitario[$key];
              $detalle->save();
            }
            DB::commit();

          }catch(\Exception $e){

            return redirect()
                ->back()
                //->withInput(Input::all())
                ->withError("Ocurrió un error al crear el presupuesto");
          }
          $request->session()->flash('message', 'Presupuesto creado con éxito.');
          $request->session()->flash('message-type', 'success');

          return response()->json(['presupuesto_id'=> $presupuesto->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Presupuesto $presupuesto
     * @return Response
     */
    public function edit(Presupuesto $presupuesto)
    {
        return view('presupuestos::admin.presupuestos.edit', compact('presupuesto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Presupuesto $presupuesto
     * @param  UpdatePresupuestoRequest $request
     * @return Response
     */
    public function update(Presupuesto $presupuesto, UpdatePresupuestoRequest $request)
    {
        $this->presupuesto->update($presupuesto, $request->all());

        return redirect()->route('admin.presupuestos.presupuesto.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('presupuestos::presupuestos.title.presupuestos')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Presupuesto $presupuesto
     * @return Response
     */
    public function destroy(Presupuesto $presupuesto)
    {
        $this->presupuesto->destroy($presupuesto);

        return redirect()->route('admin.presupuestos.presupuesto.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('presupuestos::presupuestos.title.presupuestos')]));
    }

    public function export_to_pdf(Request $request) {
            $presupuesto = Presupuesto::find($request->presupuesto_id);
            $presupuestoDetalles = [];

            $datosPresupuesto["nro_presupuesto"] = $presupuesto->nro_presupuesto;
            $datosPresupuesto["nombre_cliente"] = $presupuesto->nombre_cliente;
            $datosPresupuesto["telefono_cliente"] = $presupuesto->telefono_cliente;
            $datosPresupuesto["direccion_cliente"] = $presupuesto->direccion_cliente;
            $datosPresupuesto["email_cliente"] = $presupuesto->email_cliente;
            $datosPresupuesto["subtotal"] = number_format($presupuesto->precio_total,0,"",".");
            $datosPresupuesto["total"] = number_format($presupuesto->precio_total,0,"",".");
            $datosPresupuesto["fecha"] = Carbon::now()->format('d-m-Y');
            foreach ($presupuesto->detalles as $detalle) {
              $producto = Producto::find($detalle->producto_id);
              $pdfDetalle["producto"] = $producto->nombre;
              $pdfDetalle["cantidad"] = number_format($detalle->cantidad,0,"",".");
              $pdfDetalle["descuento"] = number_format(100-$detalle->descuento*100,0,"",".")."%";
              $pdfDetalle["precio_unitario"] = number_format($detalle->precio_unitario,0,"",".");
              $pdfDetalle["subtotal"] = number_format($detalle->cantidad * $detalle->precio_unitario * $detalle->descuento,0,"",".");
              $presupuestoDetalles[] = $pdfDetalle;

            }
            $format = $request->format;
            if($request->download ==   "true") {
                $pdf = PDF::loadView('presupuestos::pdf.presupuesto',compact('datosPresupuesto','presupuestoDetalles','format'));
                $pdf->setPaper('A4', 'portrait');
                return $pdf->download('presupuesto-'.$presupuesto->nro_presupuesto.'.pdf');
            }else {
                if($format == "pdf") {
                    $pdf = PDF::loadView('presupuestos::pdf.presupuesto',compact('datosPresupuesto','presupuestoDetalles','format'));
                    $pdf->setPaper('A4', 'portrait');
                    return $pdf->stream('presupuesto-'.$presupuesto->nro_presupuesto.'.pdf');
                }else {
                    $view = View::make('presupuestos::pdf.presupuesto',compact('datosPresupuesto','presupuestoDetalles','format'));
                    return $view->render();

                }
            }
    }

    public function download(Request $request) {

    }

    public function index_ajax(Request $re)
    {
        $query = $this->query_index_ajax($re);
        $object = Datatables::of($query)
            ->addColumn('acciones', function( $presupuesto ){
                $buttons = '<div class="btn-group">
                        <a id="detalle_'.$presupuesto->id.'" href="javascript:void(0)" class="detalle btn btn-default btn-flat"><i class="fa fa-eye"></i></a>
                        <a href="'.route('admin.presupuestos.presupuesto.exportar').'?format=pdf&download=true&presupuesto_id='.$presupuesto->id.'" class="btn btn-default btn-flat"><i class="fa fa-download"></i></a>
                    </div>';
              return $buttons;
            })
            ->editColumn('created_at', function( $presupuesto ){
              return $presupuesto->created_at->format('d/m/y');
            })
            ->rawColumns(['acciones'])
            ->make(true);
        $data = $object->getData(true);
        return response()->json( $data );
    }

    public function query_index_ajax($re){
        $query = Presupuesto::select();
        if(isset($re->nro_presupuesto) && trim($re->nro_presupuesto) != '')
          $query->where('nro_presupuesto', 'LIKE', '%'.$re->nro_presupuesto.'%');

        if(isset($re->nombre_cliente) && trim($re->nombre_cliente) != '')
          $query->where('nombre_cliente', 'LIKE', '%'.$re->nombre_cliente.'%');

        if (isset($re->fecha_desde) && trim($re->fecha_desde) != '')
          $query->whereDate('created_at', '>=', $this->fechaFormat($re->fecha_desde) );

        if (isset($re->fecha_hasta) && trim($re->fecha_hasta) != '')
          $query->whereDate('created_at', '<=', $this->fechaFormat($re->fecha_hasta) );

        return $query;
    }

    private function fechaFormat($date){
       return date("Y-m-d", strtotime( str_replace('/', '-', $date)));
    }
}
