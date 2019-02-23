<?php

namespace Modules\Clientes\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Clientes\Entities\Cliente;
use Modules\Clientes\Entities\DatosFacturacion;
use Modules\Clientes\Http\Requests\CreateClienteRequest;
use Modules\Clientes\Http\Requests\UpdateClienteRequest;
use Modules\Clientes\Repositories\ClienteRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ClienteController extends AdminBaseController
{
    /**
     * @var ClienteRepository
     */
    private $cliente;

    public function __construct(ClienteRepository $cliente)
    {
        parent::__construct();

        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $clientes = $this->cliente->all();

        return view('clientes::admin.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('clientes::admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateClienteRequest $request
     * @return Response
     */
    public function store(CreateClienteRequest $request)
    {
        $this->cliente->create($request->all());

        return redirect()->route('admin.clientes.cliente.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('clientes::clientes.title.clientes')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Cliente $cliente
     * @return Response
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes::admin.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Cliente $cliente
     * @param  UpdateClienteRequest $request
     * @return Response
     */
    public function update(Cliente $cliente, UpdateClienteRequest $request)
    {
        $this->cliente->update($cliente, $request->all());

        return redirect()->route('admin.clientes.cliente.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('clientes::clientes.title.clientes')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Cliente $cliente
     * @return Response
     */
    public function destroy(Cliente $cliente)
    {
        $this->cliente->destroy($cliente);

        return redirect()->route('admin.clientes.cliente.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('clientes::clientes.title.clientes')]));
    }


}
