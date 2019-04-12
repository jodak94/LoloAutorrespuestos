<div class="box-body">
  <div class="row">
    <div class="col-md-12" style="margin-bottom:10px">
      <label>Configuración del Número de Factura</label>
    </div>
    @foreach ($conf_factura as $key => $conf)
      <input type="hidden" name="fac_id[]" value="{{$conf->id}}">
      <div class="col-md-2">
        <div class="form-group ">
          <input name="factura[]" type="text" class="form-control" value={{$conf->value}}>
        </div>
          @if($key < 2)
            </div>
            <div class="col-md-1" style="width: 2%; padding: 0; text-align:center">
              -
            </div>
          @else
            </div>
          @endif
    @endforeach
  </div>
  <div class="row">
    @foreach ($configuracions as $conf)
      <div class="col-md-4">
        <div class="form-group ">
          <label>{{$conf->descripcion}}</label>
          @if($conf->slug == 'periodo_validez_presupuesto')
            <input name="{{$conf->slug}}" type="number" class="form-control" value={{$conf->value}}>
          @else
            <input name="{{$conf->slug}}" type="text" class="form-control" value={{$conf->value}}>
          @endif
        </div>
      </div>
    @endforeach
  </div>
</div>
