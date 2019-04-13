<div class="box-body">
  <div class="row">
    <div class="col-md-6">
      {!! Form::normalInput('slug', 'Slug', $errors, $configuracion) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('descripcion', 'Descripción', $errors, $configuracion) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('value', 'Valor', $errors, $configuracion) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('orden', 'Orden', $errors, $configuracion) !!}
    </div>
    <div class="col-md-6">
      {!! Form:: normalCheckbox('admin', 'Admin', $errors, $configuracion) !!}
    </div>
  </div>
</div>
