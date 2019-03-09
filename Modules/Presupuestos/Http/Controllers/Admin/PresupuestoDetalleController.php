<?php

namespace Modules\Presupuestos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Presupuestos\Entities\PresupuestoDetalle;
use Modules\Presupuestos\Http\Requests\CreatePresupuestoDetalleRequest;
use Modules\Presupuestos\Http\Requests\UpdatePresupuestoDetalleRequest;
use Modules\Presupuestos\Repositories\PresupuestoDetalleRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class PresupuestoDetalleController extends AdminBaseController
{
    /**
     * @var PresupuestoDetalleRepository
     */
    private $presupuestodetalle;

    public function __construct(PresupuestoDetalleRepository $presupuestodetalle)
    {
        parent::__construct();

        $this->presupuestodetalle = $presupuestodetalle;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$presupuestodetalles = $this->presupuestodetalle->all();

        return view('presupuestos::admin.presupuestodetalles.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('presupuestos::admin.presupuestodetalles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePresupuestoDetalleRequest $request
     * @return Response
     */
    public function store(CreatePresupuestoDetalleRequest $request)
    {
        $this->presupuestodetalle->create($request->all());

        return redirect()->route('admin.presupuestos.presupuestodetalle.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('presupuestos::presupuestodetalles.title.presupuestodetalles')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PresupuestoDetalle $presupuestodetalle
     * @return Response
     */
    public function edit(PresupuestoDetalle $presupuestodetalle)
    {
        return view('presupuestos::admin.presupuestodetalles.edit', compact('presupuestodetalle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PresupuestoDetalle $presupuestodetalle
     * @param  UpdatePresupuestoDetalleRequest $request
     * @return Response
     */
    public function update(PresupuestoDetalle $presupuestodetalle, UpdatePresupuestoDetalleRequest $request)
    {
        $this->presupuestodetalle->update($presupuestodetalle, $request->all());

        return redirect()->route('admin.presupuestos.presupuestodetalle.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('presupuestos::presupuestodetalles.title.presupuestodetalles')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PresupuestoDetalle $presupuestodetalle
     * @return Response
     */
    public function destroy(PresupuestoDetalle $presupuestodetalle)
    {
        $this->presupuestodetalle->destroy($presupuestodetalle);

        return redirect()->route('admin.presupuestos.presupuestodetalle.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('presupuestos::presupuestodetalles.title.presupuestodetalles')]));
    }
}
