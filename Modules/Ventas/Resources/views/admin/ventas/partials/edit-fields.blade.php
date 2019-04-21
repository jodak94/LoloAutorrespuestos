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
          {!! Form::normalInput('fecha', 'Fecha', $errors, (object)['fecha' => $factura->created_at->format('d/m/Y')], ['class' => 'form-control fecha']) !!}
        </div>
        <div class="col-md-4">
          {!! Form::normalInput('nro_factura', 'Nro. de Factura', $errors, $factura) !!}
        </div>
        <input type="hidden" name="edit" value="1">
        <input type="hidden" name="factura_a_anular" value="{{$factura->id}}">
      </div>
      <div class="row" style="margin-bottom: 20px">
        <div class="col-md-4">
          <label for="paciente_id">Buscar cliente</label>
          <div class="input-group ">
            @if($venta->generar_factura)
              <input placeholder="Ingresar Ruc o Razón social" type="text" id="buscar-datos" class="form-control">
              <input type="hidden" name="datos_id" id="datos_id" value="{{$datos_id}}">
            @else
              <input disabled placeholder="Ingresar Ruc o Razón social" type="text" id="buscar-datos" class="form-control">
              <input type="hidden" name="datos_id" id="datos_id">
            @endif
            <span class="input-group-btn">
              <button title="Agregar nuevo cliente" type="button" class="btn btn-primary btn-flat" id="add-cliente-button" style="height:34px">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
              </button>
            </span>
          </div>
        </div>
        <div class="col-md-4">
          <label>Generar Factura</label>
          {!! Form:: normalCheckbox('generar_factura', '', $errors, $factura) !!}
        </div>
      </div>
      @if($factura->generar_factura)
        <div id="datosfacturacion">
      @else
        <div id="datosfacturacion" style="display:none">
      @endif
        <div class="row" style="display:flex">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                {!! Form::normalInput('datos_razon_social', 'Razón Social', $errors, (object)['datos_razon_social' => $factura->razon_social], ['readonly' => 'true']) !!}
              </div>
              <div class="col-md-6">
                {!! Form::normalInput('datos_ruc', 'Ruc', $errors, (object)['datos_ruc' => $factura->ruc], ['readonly' => 'true']) !!}
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {!! Form::normalInput('datos_telefono', 'Teléfono', $errors, (object)['datos_telefono' => $factura->telefono], ['readonly' => 'true']) !!}
              </div>
              <div class="col-md-6">
                {!! Form::normalInput('datos_direccion', 'Dirección', $errors, (object)['datos_direccion' => $factura->direccion], ['readonly' => 'true']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-default btn-flat center" id="edit-cliente-button">
              <i class="fa fa-pencil"></i> Editar Cliente
            </button>
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
            <th>Descuento</th>
            <th>Stock</th>
            <th>SubTotal</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody id="detallesBody">
          @foreach ($factura->detalles as $detalle_key => $detalle)
            <tr>
              <td>
                <input class="buscar-producto form-control" placeholder="Buscar por nombre" required value="{{$detalle->producto->nombre}}">
                <input value="{{$detalle->producto->id}}" type="hidden" class="producto_id" name="producto_id[]">
              </td>
              <td>
                <img class="foto" src="{{$detalle->producto->url_foto}}"  width="40px" height="auto" style="display:flex; margin:auto">
              </td>
              <td>
                <input value="{{$detalle->cantidad}}" type="number" class="form-control cantidad" name="cantidad[]" required>
              </td>
              <td>
                <input value="{{$detalle->precio_unitario}}" class="form-control precio precio_format" name="precio_unitario[]" readonly>
              </td>
              <td>
                <select name="descuento[]" class="form-control descuento">
              @foreach ($descuentos as $key => $descuento) {
                @if($key == $detalle->descuento)
                    <option selected value="{{$key}}">{{$descuento}}</option>
                @else
                  <option value="{{$key}}">{{$descuento}}</option>
                @endif
              @endforeach
              </select>
              </td>
              <td>
                <input value="{{$detalle->producto->stock}}" class="form-control stock" readonly>
              </td>
              <td>
                <input value="{{$detalle->precio_subtotal}}" class="form-control subtotal precio_format" name="subtotal[]" readonly>
              </td>
              <td style="text-align:center;">
                @if($detalle_key)
                  <i class="glyphicon glyphicon-trash btn btn-danger remove-field">'
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <a href="javascript:void(0)" id="add-detalle" class="btn btn-primary btn-flat">Agregar Detalle </a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <strong>Monto total: </strong>
          <input value="{{$factura->monto_total}}" readonly name="monto_total" class="precio_format form-control" id="monto-total">
        </div>
        <div class="col-md-7">
          <strong>Monto total (en letras): </strong>
          <input value="{{$factura->precio_total_letras}}" readonly type="text" name="precio_total_letras" value="" class="form-control" id="monto-total-letras">
        </div>
        <div class="col-md-2">
          <strong>Total Iva 10%: </strong>
          <input value="{{$factura->total_iva}}"readonly name="total_iva" class="precio_float_format form-control" id="total-iva-10">
        </div>
      </div>
    </div>
  </div>
</div>
