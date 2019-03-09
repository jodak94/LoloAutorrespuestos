<div class="box-body">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Datos de Facturación</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
          <i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-4">
          <label>Nro. de Factura: </label>{{' ' . $venta->nro_factura}}
        </div>
        <div class="col-md-4">
          <label>Timbrado: </label>{{' ' . $venta->timbrado}}
        </div>
        <div class="col-md-4">
          <label>Fecha: </label>{{' ' . $venta->created_at}}
        </div>
      </div>
      <div class="row" style="display:flex; margin-top: 10px">
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6">
              <label>Razón Social: </label>{{' ' . $venta->razon_social}}
            </div>
            <div class="col-md-6">
              <label>RUC: </label>{{' ' . $venta->ruc}}
            </div>
          </div>
          <div class="row" style="display:flex; margin-top: 10px">
            <div class="col-md-6">
              <label>Teléfono: </label>{{' ' . $venta->telefono}}
            </div>
            <div class="col-md-6">
              <label>Dirección: </label>{{' ' . $venta->direccion}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Detalles de la venta</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
          <i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="box-body">
      <table id="detalles-table" class="data-table table table-bordered table-hover">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Foto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>SubTotal</th>
          </tr>
        </thead>
        <tbody id="detallesBody">
          @foreach ($venta->detalles as $detalle)
            <tr>
              <td style="width: 35%">
                {{$detalle->producto->codigo . ' - ' . $detalle->producto->nombre}}
              </td>
              <td style="width: 15%">
                <img src="{{url($detalle->producto->foto)}}" width="30px" height="auto" style="display:flex; margin:auto">
              </td>
              <td style="width: 10%">
                {{$detalle->cantidad}}
              </td>
              <td style="width: 20%">
                {{$detalle->precio_unitario}}
              </td>
              <td style="width: 20%">
                {{$detalle->precio_subtotal}}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-3">
          <label>Monto total: </label>
          {{$venta->monto_total}} Gs.
        </div>
        <div class="col-md-6">
          {{$venta->precio_total_letras}}
        </div>
        <div class="col-md-3">
          <label>Total Iva 10%: </label>
          {{$venta->total_iva}} Gs.
        </div>
      </div>
    </div>
  </div>
</div>
