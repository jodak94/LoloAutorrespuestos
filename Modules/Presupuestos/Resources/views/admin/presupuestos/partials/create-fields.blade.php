<div class="box-body">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Datos del Presupuesto</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
          <i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-4">
          {!! Form::normalInput('nro_presupuesto', 'Nro. de Presupuesto', $errors, (object)['nro_presupuesto' => $nro_presupuesto], ['readonly' => 'true']) !!}
        </div>
        <div class="col-md-4">
          {!! Form::normalInputOfType('date','fecha', 'Fecha', $errors, (object)['fecha' => Carbon\Carbon::now()->format('Y-m-d')], ['readonly' => 'true']) !!}
        </div>
      </div>
            <div class="row">
              <div class="col-md-6">
                {!! Form::normalInput('nombre_cliente', 'Nombre del Cliente', $errors, null,['required' => 'true'])!!}
              </div>
              <div class="col-md-6">
                {!! Form::normalInput('telefono_cliente', 'Teléfono', $errors, null) !!}
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                {!! Form::normalInput('direccion_cliente', 'Dirección', $errors, null) !!}
              </div>
              <div class="col-md-6">
                {!! Form::normalInput('email_cliente', 'E-mail', $errors, null) !!}
              </div>
            </div>

     
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Detalles del Presupuesto</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse">
          <i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    @php
      $row = '<tr>
        <td>
          <input class="buscar-producto form-control" required>
          <input type="hidden" class="producto_id" name="producto_id[]">
        </td>
        <td>
          <input readonly type="number" class="form-control cantidad" name="cantidad[]" required>
        </td>
        <td>
          <input class="form-control precio" name="precio_unitario[]" readonly>
        </td>
        <td>
          <select name="descuento[]" class="form-control descuento"> ';
        foreach ($descuentos as $key => $descuento) {
          $row .= '<option value="'.$key.'">'.$descuento.'</option>';
        }
        $row .=
        ' </select>
        </td>
        <td>
          <input class="form-control subtotal" readonly>
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
            <th>Descuento</th>
            <th>Subtotal</th>
            <th> </th>
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
          <strong>Total: </strong>
          <input readonly type="number" name="precio_total" value="0" class="form-control" id="total">
        </div>
      </div>
    </div>
  </div>
</div>
