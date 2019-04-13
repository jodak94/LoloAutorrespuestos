<div class="box-body">
  <div class="row">
    <div class="col-md-8" style="padding:0">
      <div class="col-md-6">
        {!! Form::normalInput('codigo', 'Codigo', $errors,null,['required'=> 'required']) !!}
      </div>
      <div class="col-md-6">
        {!! Form::normalInput('nombre', 'Nombre', $errors,null,['required'=> 'required']) !!}
      </div>
      <div class="col-md-12">.
        <div class="form-group ">
          <label for="codigo">Descripción</label>
          <textarea id="descripcion" name="descripcion" placeholder="Descripción" style="resize:none;width:100%;" class="form-control" rows="5"></textarea>
        </div>
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
  <div class="row">
    <div class="col-md-4">
      {!! Form::normalInputOfType('number','stock', 'Stock', $errors,null,['required'=> 'required']) !!}
    </div>
    <div class="col-md-4">
      {!! Form::normalInputOfType('number','stock_critico', 'Stock Critico', $errors,null,['required'=> 'required']) !!}
    </div>
    <div class="col-md-4">
      {!! Form::normalInput('precio', 'Precio', $errors, null, ['class'=>'form-control precio','required'=> 'required']) !!}
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      {!! Form::normalInput('costo', 'Costo', $errors,null,['class'=>'form-control costo','required'=> 'required']) !!}
    </div>
    <div class="col-md-4">
      {!! Form::normalInputOfType('number','descuento', 'Descuento', $errors) !!}
    </div>

  </div>
</div>
