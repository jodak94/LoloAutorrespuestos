<?php

namespace Modules\Productos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Productos\Entities\Producto;
use Modules\Productos\Http\Requests\CreateProductoRequest;
use Modules\Productos\Http\Requests\UpdateProductoRequest;
use Modules\Productos\Repositories\ProductoRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use \Excel;
use App\Imports\ProductosImport;
use Validator;

class ProductoController extends AdminBaseController
{
    /**
     * @var ProductoRepository
     */
    private $producto;
    private $rules;
    private $messages;

    public function __construct(ProductoRepository $producto)
    {
        parent::__construct();

        $this->producto = $producto;
        $this->rules = [
            'codigo' => 'required|unique:productos__productos',
            'nombre'  => 'required',
            'descripcion' => '',
            'precio'     => 'required|numeric|min:0',
            'stock'     => 'required|numeric|min:0',
            'stock_critico' => 'required|numeric|min:0',
            'costo' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0'
        ];
        $this->messages = [
            'min'    => 'El campo :attribute debe ser mayor o igual a cero.',
            'required'    => 'El campo :attribute no puede quedar vacio.',
            'unique' => 'El campo :attribute debe ser único. Ya existe ese valor.',
            'numeric' => 'El campo :attribute debe ser numérico.',
        ];

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
        $descuentos = (array)json_decode(\Configuracion::where('slug', 'descuentos')->first()->value);

        return view('productos::admin.productos.create', compact('descuentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductoRequest $request
     * @return Response
     */
    public function store(CreateProductoRequest $request)
    {
        $validator = Validator::make($request->all(),$this->rules,$this->messages);

        if ($validator->fails()) {
            return redirect()->route('admin.productos.producto.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $producto = $this->producto->create($request->all());
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $pathToFile = "assets/media/fotos_productos/$fileName" ;
            Storage::disk('local')->put('public'.'/'.$pathToFile, file_get_contents($image));
            $producto->foto = $pathToFile;
            $producto->save();
        }
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
        $descuentos = (array)json_decode(\Configuracion::where('slug', 'descuentos')->first()->value);
        return view('productos::admin.productos.edit', compact('producto', 'descuentos'));
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
        $updateRules = $this->rules;
        if($producto->codigo == $request->codigo) {
            $updateRules['codigo'] = str_replace('|unique:productos__productos',"",$updateRules['codigo']);
        }

        $validator = Validator::make($request->all(),$updateRules,$this->messages);

        if ($validator->fails()) {
            return redirect()->route('admin.productos.producto.edit',['producto'=>$producto->id])
                        ->withErrors($validator)
                        ->withInput();
        }
      $producto = $this->producto->update($producto, $request->all());
      $producto->precio = $request->precio;
      $producto->save();
        if ($request->hasFile('image')) {
            $oldImage = $producto->foto;
            if(File::exists($oldImage)) {
                File::delete($oldImage);
            }
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $pathToFile = "assets/media/fotos_productos/$fileName" ;
            Storage::disk('local')->put('public'.'/'.$pathToFile, file_get_contents($image));

            $producto->foto = $pathToFile;
            $producto->save();
        }
        return redirect()->route('admin.productos.producto.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('productos::productos.title.productos')]));
    }

    private function number_format($number){
        return str_replace(',', '.',str_replace('.', '', $number));
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
      $query = Producto::Where('nombre','like',  '%' . $re->term . '%');
      $query->orWhere('codigo','like', '%'. $re->term .'%');
      $productos = $query->take(5)->get();
      $results = [];
      foreach ($productos as $producto){
        $results[] =
        [
          'producto' => $producto,
          'label' => $producto->codigo. ' - ' .$producto->nombre,
          'value' => $producto->nombre,
        ];
      }
      return response()->json($results);
    }
    public function update_stock(Request $request) {
        foreach($request->producto_id as $key => $producto_id) {
            $producto = Producto::find($producto_id);
            if(isset($producto)) {
                $producto->stock += $request->cantidad[$key];
                $producto->save();
            }
        }
        return redirect()->route('admin.productos.producto.index')
            ->withSuccess("Stock actualizado correctamente");
    }

    public function entrada()
    {
        return view('productos::admin.productos.entrada');
    }

    public function import_view()
    {
        return view('productos::admin.productos.import');
    }

    public function import_productos(Request $request) {

        $extensions = array("xls","xlsx");

        $result = array($request->file('excel')->getClientOriginalExtension());

        if(in_array($result[0],$extensions)){
            $rows = Excel::toArray(new ProductosImport, request()->file('excel'));
            $errors = [];
            $productos_error = [];
            $productos_cargados = 0;
            foreach($rows as $row) {
                foreach($row as $producto) {
                    $error = $this->cell_validation($producto, $this->rules);
                        if (!empty($error)) {
                            $errors[] = $error;
                            $productos_error[] = $producto;
                        }else {
                            $nuevo_producto = new Producto();
                            $nuevo_producto->codigo = $producto["codigo"];
                            $nuevo_producto->nombre = $producto["nombre"];
                            $nuevo_producto->descripcion = $producto["descripcion"];
                            $nuevo_producto->stock = $producto["stock"];
                            $nuevo_producto->stock_critico = $producto["stock_critico"];
                            $nuevo_producto->precio = $producto["precio"];
                            $nuevo_producto->costo = $producto["costo"];
                            $nuevo_producto->descuento = $producto["descuento"]?$producto["descuento"]/100:0;
                            $nuevo_producto->save();
                            $productos_cargados++;
                        }
                }
            }
            return response()->json([
                "cargados" => $productos_cargados,
                "productos" => $productos_error,
                "errores" => $errors
            ]);
        }else{
            return response()->json([
                "error" => "error en tipo de archivo",
            ],400);
        }


    }

    public function producto_validation(Request $request) {
        $error = $this->cell_validation($request->producto,$this->rules);
        if (!empty($error)) {

            return response()->json([
                'status' => false,
                'error' => $error[0]
            ],400);
        }
        return response()->json([
            'status' => true,
        ]);
    }

    protected function cell_validation(array $data, array $rules)
    {
        // Perform Validation
        $validator = \Validator::make($data, $rules,$this->messages);
        $errors = [];
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->messages();
            foreach ($errorMessages as $key => $value) {
                $error[$key] = $value[0];
            }
            $errors[] = $error;
            return $errors;
        }
        return [];
    }

    public function store_ajax(Request $request) {
        $productos_cargados = 0;
        foreach($request->productos as $req) {
            if($req != null){
                $producto = new Producto();
                $producto->codigo = $req["codigo"];
                $producto->nombre = $req["nombre"];
                $producto->descripcion = $req["descripcion"];
                $producto->stock = $req["stock"];
                $producto->stock_critico = $req["stock_critico"];
                $producto->precio = $req["precio"];
                $producto->costo = $req["costo"];
                $producto->descuento = $req["descuento"]?(1-$req["descuento"]/100):0;
                $producto->save();
                $productos_cargados++;
            }
        }
        $request->session()->flash('message', 'Nuevos Productos agregados.');
        $request->session()->flash('message-type', 'success');
        return response()->json(['cargados'=>$productos_cargados]);
    }
}
