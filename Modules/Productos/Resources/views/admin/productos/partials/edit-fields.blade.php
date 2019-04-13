<div class="box-body">
  <div class="row">
    <div class="col-md-8" style="padding:0">
      <div class="col-md-6">
        {!! Form::normalInput('nombre', 'Nombre', $errors, $producto) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInput('codigo', 'Codigo', $errors, $producto) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInputOfType('number','stock', 'Stock', $errors, $producto) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInputOfType('number','stock_critico', 'Stock Critico', $errors, $producto) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInput('costo', 'Costo', $errors, $producto, ['class'=>'form-control precio']) !!}
      </div>
      <div class="col-md-4">
        {!! Form::normalInput('precio', 'Precio', $errors, $producto, ['class'=>'form-control precio']) !!}
      </div>
      <div class="col-md-2">
        {!! Form:: normalSelect('descuento', 'Descuento', $errors, $descuentos, $producto) !!}
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('image','Foto')!!}
        {!! Form::file('image',['class' => 'form-control','id' => 'img-input']) !!}
        <img id="preview" src="{{$producto->url_foto}}" width="150" height="150" style="display: flex; margin:auto; margin-top:20px;object-fit:cover"/>
      </div>
    </div>
  </div>
</div>
