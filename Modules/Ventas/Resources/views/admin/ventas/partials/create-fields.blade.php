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
          {!! Form::normalInput('nro_factura', 'Nro. de Factura', $errors, (object)['nro_factura' => $nro_factura], ['readonly' => 'true']) !!}
        </div>
        <div class="col-md-4">
          {!! Form::normalInput('timbrado', 'Timbrado', $errors, (object)['timbrado' => '123456789'], ['readonly' => 'true']) !!}
        </div>
        <div class="col-md-4">
          {!! Form::normalInputOfType('date','fecha', 'Fecha', $errors, (object)['fecha' => Carbon\Carbon::now()->format('Y-m-d')], ['readonly' => 'true']) !!}
        </div>
      </div>
      <div class="row" style="margin-bottom: 20px">
        <div class="col-md-4">
          <label for="paciente_id">Buscar cliente</label>
          <div class="input-group ">
            <input placeholder="Ingresar Ruc o Razón social" type="text" id="buscar-datos" class="form-control">
            <input type="hidden" name="datos_id" id="datos_id">
            <span class="input-group-btn">
              <button title="Agregar nuevo cliente" type="button" class="btn btn-primary btn-flat" id="add-cliente-button" style="height:34px">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
              </button>
            </span>
          </div>
        </div>
      </div>
      <div id="datosfacturacion" style="display:none">
      {{-- <div id="datosfacturacion"> --}}
        <div class="row" style="display:flex">
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                {!! Form::normalInput('datos_razon_social', 'Razón Social', $errors, null, ['readonly' => 'true']) !!}
              </div>
              <div class="col-md-6">
                {!! Form::normalInput('datos_ruc', 'Ruc', $errors, null, ['readonly' => 'true']) !!}
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {!! Form::normalInput('datos_telefono', 'Teléfono', $errors, null, ['readonly' => 'true']) !!}
              </div>
              <div class="col-md-6">
                {!! Form::normalInput('datos_direccion', 'Dirección', $errors, null, ['readonly' => 'true']) !!}
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
    @php
      $row = '<tr>
        <td>
          <input class="buscar-producto form-control" placeholder="Buscar por nombre" required>
          <input type="hidden" class="producto_id" name="producto_id[]">
        </td>
        <td>
          <input readonly type="number" class="form-control cantidad" name="cantidad" required>
        </td>
        <td>
          <input class="form-control precio" name="precio_unitario[]" readonly>
        </td>
        <td>
          <input class="form-control iva" readonly>
        </td>
        <td>
          <input class="form-control stock" readonly>
        </td>
        <td>
          <input class="form-control subtotal" name="subtotal[]" readonly>
        </td>
        <td>
        </td>
      </tr>'
    @endphp
    <div class="box-body">
      <table id="detalles-table" class="data-table table table-bordered table-hover">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Iva</th>
            <th>Stock</th>
            <th>SubTotal</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody id="detallesBody">
          @php
            echo $row;
          @endphp
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
          <input readonly type="number" name="monto_total" value="0" class="form-control" id="monto-total">
        </div>
        {{-- <div class="col-md-7"> --}}
          {{-- {!! Form::normalInput('monto-total-letras', 'Monto total', $errors,null,['class'=>'form-control input-sm','readonly'=>'readonly']) !!} --}}
        {{-- </div> --}}
        <div class="col-md-2">
          <strong>Total Iva 10%: </strong>
          <input readonly type="number" name="total_iva_10" value="0" class="form-control" id="total-iva-10">
        </div>
      </div>
    </div>
  </div>
</div>
