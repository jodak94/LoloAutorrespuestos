<?php

namespace Modules\Productos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Productos\Entities\Producto;
use Modules\Configuracion\Entities\Configuracion;
use Modules\Productos\Http\Requests\CreateProductoRequest;
use Modules\Productos\Http\Requests\UpdateProductoRequest;
use Modules\Productos\Repositories\ProductoRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use \Excel;
use App\Imports\ProductosImport;
use App\Exports\ProductosExport;
use Validator;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Log;
use Auth;
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
            'nombre'  => 'required',
            'codigo' => 'unique:productos__productos',
            'descripcion' => 'nullable',
            'precio'     => 'required|min:0',
            'stock'     => 'required|numeric|min:0',
            'stock_critico' => 'nullable|numeric|min:0',
            'costo' => 'nullable|min:0',
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

    public function index_ajax(Request $re){
      $query = $this->query_index_ajax($re);
      $ventas = $query->get();
      $object = Datatables::of($query)
          ->editColumn('url_foto', function($producto){
            $html = '<img src="'.$producto->url_foto.'" width="40px" height="auto" class="foto" style="display: flex; margin: auto;">';
            return $html;
          })
          ->addColumn('acciones', function( $producto ){
            $edit_route = route('admin.productos.producto.edit', $producto->id);
            $delete_route = route('admin.productos.producto.destroy', $producto->id);
            $html = '
              <div class="btn-group">
                <a href="'.$edit_route.'" class="btn btn-default btn-flat">
                  <i class="fa fa-pencil"></i>
                </a>';
            if(Auth::user()->hasRoleSlug('administrador') || Auth::user()->hasRoleSlug('admin'))
              $html .= '
              <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="'.$delete_route.'">
                <i class="fa fa-trash">
              </i></button>';

            $html .=  '</div>';
            return $html;
          })
          ->rawColumns(['acciones', 'url_foto'])
          ->make(true);
      $data = $object->getData(true);
      return response()->json( $data );
    }

    public function query_index_ajax($re){
        $query = Producto::select();
        if(isset($re->codigo) && trim($re->codigo) != '')
          $query->where('codigo', 'LIKE', '%'.$re->codigo.'%');

        if(isset($re->nombre) && trim($re->nombre) != '')
          $query->where('nombre', 'LIKE', '%'.$re->nombre.'%');

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $descuentos = (array)json_decode(Configuracion::where('slug', 'descuentos')->first()->value);

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
        $descuentos = (array)json_decode(Configuracion::where('slug', 'descuentos')->first()->value);
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
        $updateRules['codigo'] = '|unique:productos__productos,codigo, '.$producto->id;
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
        try{
          $this->producto->destroy($producto);
        }catch(\Exception $e){
          return redirect()->route('admin.productos.producto.index')
              ->withError('Error al eliminar el producto, existen ventas asociadas al producto.');
        }

        return redirect()->route('admin.productos.producto.index')
            ->withSuccess('Producto eliminado exitosamente');
    }

    public function search_ajax(Request $re){
      $re['term_explode'] = explode(' ',$re->term);
      $query = Producto::Where('nombre','like',  '%' . $re->term . '%');
      $query->orWhere('codigo','like', '%'. $re->term .'%');
      $productos = $query->take(10)->get();
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
          DB::beginTransaction();
          try{
            $rows = Excel::toArray(new ProductosImport, request()->file('excel'));
            $errors = [];
            $productos_error = [];
            $productos_cargados = 0;
            $c = 0;
            foreach($rows as $row) {
                foreach($row as $producto) {
                    $c++;
                    $error = $this->cell_validation($producto, $this->rules);
                        if (!empty($error)) {
                            $errors[] = $error;
                            $productos_error[] = $producto;
                        }else {
                            $nuevo_producto = new Producto();
                            if($producto["codigo"])
                              $nuevo_producto->codigo = $producto["codigo"];
                            else
                              $nuevo_producto->codigo = $this->obtener_codigo($producto["nombre"]);
                            $nuevo_producto->nombre = $producto["nombre"];
                            $nuevo_producto->stock = $producto["stock"];
                            $nuevo_producto->stock_critico = $producto["stock_critico"];
                            $nuevo_producto->precio = $producto["precio"];
                            $nuevo_producto->costo = $producto["costo"];
                            $nuevo_producto->descripcion = $producto["descripcion"];
                            if($producto["descuento"]) {
                                $descuento = strval(1-$producto["descuento"]/100);
                                $confDescuento = Configuracion::where('slug', 'descuentos')->first();
                                $descuentos = json_decode($confDescuento->value);

                                if(!array_key_exists($descuento,$descuentos)){
                                    $descuentos->$descuento = $producto["descuento"]."%";
                                    $confDescuento->value = json_encode($descuentos);
                                    $confDescuento->save();
                                }
                                $nuevo_producto->descuento = $descuento;
                            }else{
                                $nuevo_producto->descuento = 1;
                            }

                            $nuevo_producto->save();
                            $productos_cargados++;
                        }
                }
            }
            DB::commit();
            return response()->json([
                "cargados" => $productos_cargados,
                "productos" => $productos_error,
                "errores" => $errors
            ]);
          } catch (\Exception $e) {
              Log::info($e);
              return response()->json([
                "error" => "error en tipo de archivo",
              ],400);
          }
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
                if($req["codigo"])
                  $producto->codigo = $req["codigo"];
                else
                  $producto->codigo = $this->obtener_codigo($req["nombre"]);
                $producto->nombre = $req["nombre"];
                $producto->stock = $req["stock"];
                $producto->stock_critico = $req["stock_critico"];
                $producto->precio = $req["precio"];
                $producto->costo = $req["costo"];
                $producto->descripcion = $req["descripcion"];
                if($req["descuento"]) {
                    $descuento = strval(1-$req["descuento"]/100);
                    $confDescuento = Configuracion::where('slug', 'descuentos')->first();
                    $descuentos = json_decode($confDescuento->value);

                    if(!array_key_exists($descuento,$descuentos)){
                        $descuentos->$descuento = $req["descuento"]."%";
                        $confDescuento->value = json_encode($descuentos);
                        $confDescuento->save();
                    }
                    $producto->descuento = $descuento;
                }else{
                    $producto->descuento = 1;
                }

                $producto->save();
                $productos_cargados++;
            }
        }
        $request->session()->flash('message', 'Nuevos Productos agregados.');
        $request->session()->flash('message-type', 'success');
        return response()->json(['cargados'=>$productos_cargados]);
    }

    public function export() {
        return Excel::download(new ProductosExport, 'Inventario '.Carbon::now()->format('d-m-Y').'.xlsx');
    }

    private function obtener_codigo($nombre){
      $words = explode(' ', $nombre);
      $letters = '';
      if(count($words) == 1){
        if(strlen($nombre) == 1)
          return response()->json(['error' => true, 'message' => 'El nombre del producto debe tener al menos 2 caracteres']);
        $letters = substr($nombre, 0, 2);
      }else{
        foreach ($words as $key => $word) {
          if($word != "") {
            $letters .= $word[0];
            if($key == 2)
              break;
          }
        }
      }
      while (true) {
        $code = strtoupper($letters) . rand(1000, 9999);
        if(!count(Producto::where('codigo', $code)->get()))
          break;
      }
      return $code;
    }

    public function generate_code(Request $request){
      if($request->has('nombre') && trim($request->nombre) == '')
        return response(['error' => true]);
      $nombre = $request->nombre;
      $code = $this->obtener_codigo($nombre);
      return response()->json(['error' => false, 'codigo' => $code]);
    }
}
