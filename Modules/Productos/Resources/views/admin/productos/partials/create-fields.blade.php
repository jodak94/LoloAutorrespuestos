<div class="box-body">
  <div class="row">
    <div class="col-md-8" style="padding:0">
      <div class="col-md-6">
        {!! Form::normalInput('nombre', 'Nombre', $errors) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInput('codigo', 'Codigo', $errors) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInputOfType('number','stock', 'Stock', $errors) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInputOfType('number','stock_critico', 'Stock Critico', $errors) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInput('costo', 'Costo', $errors, null, ['class'=>'form-control precio']) !!}
      </div>
      <div class="col-md-4">
        {!! Form::normalInput('precio', 'Precio', $errors, null, ['class'=>'form-control precio']) !!}
      </div>
      <div class="col-md-2">
        {!! Form:: normalSelect('descuento', 'Descuento', $errors, $descuentos) !!} 
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        {!! Form::label('image','Foto')!!}
        {!! Form::file('image',['class' => 'form-control','id' => 'img-input']) !!}
        <img id="preview" src="{{url('/images/default-product.jpg')}}" width="150" height="150" style="display: flex; margin:auto; margin-top:20px;object-fit:cover"/>
      </div>
    </div>
  </div>
</div>
