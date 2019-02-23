<div class="box-body">
    <p>
        {!! Form::normalInput('codigo', 'Codigo', $errors, $producto) !!}
    {!! Form::normalInput('nombre', 'Nombre', $errors, $producto) !!}
    <textarea id="descripcion" name="descripcion" placeholder="Descripción del Producto" style="resize:none;width:100%;" class="form-group" rows="10">{{$producto->descripcion}}</textarea>
    {!! Form::normalInputOfType('number','stock', 'Stock', $errors, $producto) !!}
    {!! Form::normalInputOfType('number','stock_critico', 'Stock Critico', $errors, $producto) !!}
    {!! Form::normalInputOfType('number','costo', 'Costo', $errors, $producto) !!}
    {!! Form::normalInputOfType('number','precio', 'Precio', $errors, $producto) !!}
    </p>
</div>
