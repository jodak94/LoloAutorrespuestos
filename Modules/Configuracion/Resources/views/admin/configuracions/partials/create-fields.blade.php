<div class="box-body">
  <div class="row">
    <div class="col-md-6">
      {!! Form::normalInput('slug', 'Slug', $errors) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('descripcion', 'Descripción', $errors) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('value', 'Valor', $errors) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('orden', 'Orden', $errors) !!}
    </div>
    <div class="col-md-6">
      {!! Form:: normalCheckbox('admin', 'Admin', $errors) !!}
    </div>
  </div>
</div>
