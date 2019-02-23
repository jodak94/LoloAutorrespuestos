<?php

namespace Modules\Clientes\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Clientes\Entities\DatosFacturacion;
use Modules\Clientes\Http\Requests\CreateDatosFacturacionRequest;
use Modules\Clientes\Http\Requests\UpdateDatosFacturacionRequest;
use Modules\Clientes\Repositories\DatosFacturacionRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class DatosFacturacionController extends AdminBaseController
{
    /**
     * @var DatosFacturacionRepository
     */
    private $datosfacturacion;

    public function __construct(DatosFacturacionRepository $datosfacturacion)
    {
        parent::__construct();

        $this->datosfacturacion = $datosfacturacion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$datosfacturacions = $this->datosfacturacion->all();

        return view('clientes::admin.datosfacturacions.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('clientes::admin.datosfacturacions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateDatosFacturacionRequest $request
     * @return Response
     */
    public function store(CreateDatosFacturacionRequest $request)
    {
        $this->datosfacturacion->create($request->all());

        return redirect()->route('admin.clientes.datosfacturacion.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('clientes::datosfacturacions.title.datosfacturacions')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DatosFacturacion $datosfacturacion
     * @return Response
     */
    public function edit(DatosFacturacion $datosfacturacion)
    {
        return view('clientes::admin.datosfacturacions.edit', compact('datosfacturacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DatosFacturacion $datosfacturacion
     * @param  UpdateDatosFacturacionRequest $request
     * @return Response
     */
    public function update(DatosFacturacion $datosfacturacion, UpdateDatosFacturacionRequest $request)
    {
        $this->datosfacturacion->update($datosfacturacion, $request->all());

        return redirect()->route('admin.clientes.datosfacturacion.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('clientes::datosfacturacions.title.datosfacturacions')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DatosFacturacion $datosfacturacion
     * @return Response
     */
    public function destroy(DatosFacturacion $datosfacturacion)
    {
        $this->datosfacturacion->destroy($datosfacturacion);

        return redirect()->route('admin.clientes.datosfacturacion.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('clientes::datosfacturacions.title.datosfacturacions')]));
    }
}
