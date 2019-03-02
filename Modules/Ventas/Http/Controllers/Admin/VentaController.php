<?php

namespace Modules\Ventas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ventas\Entities\Venta;
use Modules\Ventas\Entities\VentaDetalle;
use Modules\Ventas\Http\Requests\CreateVentaRequest;
use Modules\Ventas\Http\Requests\UpdateVentaRequest;
use Modules\Ventas\Repositories\VentaRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use DB;

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
    public function index()
    {
        return view('ventas::admin.ventas.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $nro_factura = '001-034-001123';
        $tipos_factura = Venta::$tipos_factura;
        return view('ventas::admin.ventas.create', compact('nro_factura', 'tipos_factura'));
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
        $request->razon_social = $request->datos_razon_social;
        $request->ruc = $request->datos_ruc;
        $request->direccion = $request->datos_direccion;
        $request->telefono = $request->datos_telefono;
        //$f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        //$request->precio_total_letras = $f->format($request->monto_total);
        //dd($request->precio_total_letras);
        $venta = $this->venta->create($request->all());
        foreach ($request->producto_id as $key => $producto_id) {
          $detalle = new VentaDetalle();
          $detalle->venta_id = $venta->id;
          $detalle->producto_id = $producto_id;
          $detalle->cantidad = $request->cantidad[$key];
          $detalle->precio_unitario = $request->precio_unitario[$key];
          $detalle->save();
        }
        DB::commit();
      }catch(\Exception $e){
        return redirect()
            ->back()
            ->withInput(Input::all())
            ->withError("Ocurrió un error al crear la venta");
      }
      return redirect()->route('admin.ventas.venta.index')
          ->withSuccess('Venta creado exitosamente');
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
}
