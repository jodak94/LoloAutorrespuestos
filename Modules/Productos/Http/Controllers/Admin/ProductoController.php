<?php

namespace Modules\Productos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Productos\Entities\Producto;
use Modules\Productos\Http\Requests\CreateProductoRequest;
use Modules\Productos\Http\Requests\UpdateProductoRequest;
use Modules\Productos\Repositories\ProductoRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ProductoController extends AdminBaseController
{
    /**
     * @var ProductoRepository
     */
    private $producto;

    public function __construct(ProductoRepository $producto)
    {
        parent::__construct();

        $this->producto = $producto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $productos = $this->producto->all();

        return view('productos::admin.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('productos::admin.productos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductoRequest $request
     * @return Response
     */
    public function store(CreateProductoRequest $request)
    {
        $this->producto->create($request->all());

        return redirect()->route('admin.productos.producto.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('productos::productos.title.productos')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Producto $producto
     * @return Response
     */
    public function edit(Producto $producto)
    {
        return view('productos::admin.productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Producto $producto
     * @param  UpdateProductoRequest $request
     * @return Response
     */
    public function update(Producto $producto, UpdateProductoRequest $request)
    {
        $this->producto->update($producto, $request->all());

        return redirect()->route('admin.productos.producto.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('productos::productos.title.productos')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Producto $producto
     * @return Response
     */
    public function destroy(Producto $producto)
    {
        $this->producto->destroy($producto);

        return redirect()->route('admin.productos.producto.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('productos::productos.title.productos')]));
    }

    public function search_ajax(Request $re){
      $re['term_explode'] = explode(' ',$re->term);
      $query = Producto::
        Where('nombre','like',  '%' . $re->term . '%');
      $productos = $query->take(5)->get();
      $results = [];
      foreach ($productos as $producto){
        $results[] =
        [
          'producto' => $producto,
          'value' => $producto->nombre,
        ];
      }
      return response()->json($results);
    }
}
