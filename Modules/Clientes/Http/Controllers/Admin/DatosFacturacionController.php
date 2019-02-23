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

    public function store_ajax(Request $request){
      if($request->telefono == '')
        unset($request->telefono);
      if($request->direccion == '')
        unset($request->direccion);

      $datos = $this->datosfacturacion->create($request->all());
      return response()->json(['error' => false, 'datos' => $datos]);
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

    public function update_ajax(Request $request){
      if($request->telefono == '')
        unset($request->telefono);
      if($request->direccion == '')
        unset($request->direccion);

      $datosfacturacion = DatosFacturacion::find($request->datos_facturacion_id);
      if(isset($datosfacturacion))
        $this->datosfacturacion->update($datosfacturacion, $request->all());
      else
        return response()->json(['error' => true, 'message' => 'Ocurrió un error al crear los datos de facturación']);
      return response()->json(['error' => false, 'datos' => $datosfacturacion]);
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

    public function search_ajax(Request $re){
      $re['term_explode'] = explode(' ',$re->term);
      $query_datos = DatosFacturacion::
        Where('ruc','like',  '%' . $re->term . '%')
        ->With('cliente');
      $query_datos->orWhere('razon_social','like',  '%' . $re->term_explode[0] . '%');
      $datos = $query_datos->take(5)->get();
      $results = [];
      foreach ($datos as $q){
        $results[] =
        [
          'datos' => $q,
          'value' => $q->razon_social.'. Ruc: ' . $q->ruc,
        ];
      }
      return response()->json($results);
    }
}
