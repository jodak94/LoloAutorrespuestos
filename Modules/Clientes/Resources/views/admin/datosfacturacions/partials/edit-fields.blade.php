<div class="box-body">
  <div class="row">
    <div class="col-md-6">
      {!! Form::normalInput('razon_social', 'Razon Social', $errors, $datosFacturacion) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('ruc', 'Ruc', $errors, $datosFacturacion) !!}
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      {!! Form::normalInput('telefono', 'Telefono', $errors, $datosFacturacion) !!}
    </div>
    <div class="col-md-6">
      {!! Form::normalInput('direccion', 'Direccion', $errors, $datosFacturacion) !!}
    </div>
  </div>
</div>
