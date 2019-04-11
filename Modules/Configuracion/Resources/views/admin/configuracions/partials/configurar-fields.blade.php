<div class="box-body">
  <div class="row">
    <div class="col-md-9 col-md-offset-3" style="margin-bottom:10px">
      <label>Configuración del Número de Factura</label>
    </div>
    @foreach ($conf_factura as $conf)
      <input type="hidden" name="fac_id[]" value="{{$conf->id}}">
      <div class="col-md-3">
        <div class="form-group ">
          <input name="factura[]" type="text" class="form-control" value={{$conf->value}}>
        </div>
      </div>
    @endforeach
  </div>
</div>
