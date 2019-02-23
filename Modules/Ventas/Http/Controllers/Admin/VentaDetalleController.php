<?php

namespace Modules\Ventas\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Ventas\Entities\VentaDetalle;
use Modules\Ventas\Http\Requests\CreateVentaDetalleRequest;
use Modules\Ventas\Http\Requests\UpdateVentaDetalleRequest;
use Modules\Ventas\Repositories\VentaDetalleRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class VentaDetalleController extends AdminBaseController
{
    /**
     * @var VentaDetalleRepository
     */
    private $ventadetalle;

    public function __construct(VentaDetalleRepository $ventadetalle)
    {
        parent::__construct();

        $this->ventadetalle = $ventadetalle;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$ventadetalles = $this->ventadetalle->all();

        return view('ventas::admin.ventadetalles.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('ventas::admin.ventadetalles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateVentaDetalleRequest $request
     * @return Response
     */
    public function store(CreateVentaDetalleRequest $request)
    {
        $this->ventadetalle->create($request->all());

        return redirect()->route('admin.ventas.ventadetalle.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('ventas::ventadetalles.title.ventadetalles')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  VentaDetalle $ventadetalle
     * @return Response
     */
    public function edit(VentaDetalle $ventadetalle)
    {
        return view('ventas::admin.ventadetalles.edit', compact('ventadetalle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  VentaDetalle $ventadetalle
     * @param  UpdateVentaDetalleRequest $request
     * @return Response
     */
    public function update(VentaDetalle $ventadetalle, UpdateVentaDetalleRequest $request)
    {
        $this->ventadetalle->update($ventadetalle, $request->all());

        return redirect()->route('admin.ventas.ventadetalle.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('ventas::ventadetalles.title.ventadetalles')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  VentaDetalle $ventadetalle
     * @return Response
     */
    public function destroy(VentaDetalle $ventadetalle)
    {
        $this->ventadetalle->destroy($ventadetalle);

        return redirect()->route('admin.ventas.ventadetalle.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('ventas::ventadetalles.title.ventadetalles')]));
    }
}
