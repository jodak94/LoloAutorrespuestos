<div class="box-body">
    <p>
        {!! Form::normalInput('codigo', 'Codigo', $errors, $producto) !!}
    {!! Form::normalInput('nombre', 'Nombre', $errors, $producto) !!}
    <textarea id="descripcion" name="descripcion" placeholder="Descripción del Producto" style="resize:none;width:100%;" class="form-group" rows="10">{{$producto->descripcion}}</textarea>
    {!! Form::normalInputOfType('number','stock', 'Stock', $errors, $producto) !!}
    {!! Form::normalInputOfType('number','stock_critico', 'Stock Critico', $errors, $producto) !!}
    {!! Form::normalInputOfType('number','costo', 'Costo', $errors, $producto) !!}
    {!! Form::normalInputOfType('number','precio', 'Precio', $errors, $producto) !!}
    <div class="form-group">
    {!! Form::label('image','Foto')!!}
    <?php if (isset($producto->foto) && $producto->foto != ""): ?>
        <img id="preview" src="{{url($producto->foto)}}" width="150" height="150" style="margin:2%;display:block;object-fit:cover"/>
    <?php else: ?>                                    
        <img id="preview" src="#" width="150" height="150" style="margin:2%;display:none;object-fit:cover"/>
    <?php endif; ?>
    
    {!! Form::file('image',['class' => 'form-control','id' => 'img-input']) !!}
    </div>
    </p>
</div>
