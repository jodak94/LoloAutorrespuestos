@php
      $row = '<tr>
        <td>
          <input class="buscar-producto form-control" placeholder="Buscar por código o nombre" required>
          <input type="hidden" class="producto_id" name="producto_id[]">
        </td>
        <td>
          <input class="form-control stock" readonly>
        </td>
        <td>
          <input readonly type="number" class="form-control cantidad" name="cantidad[]" required>
        </td>
        <td>
        </td>
      </tr>'
    @endphp
    <div class="box-body">
      <table id="entradas-table" class="data-table table table-bordered table-hover">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Stock Actual</th>
            <th>Cantidad</th>
            <th>    </th>
          </tr>
        </thead>
        <tbody id="entradasBody">
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
            <a href="javascript:void(0)" id="add-entrada" class="btn btn-primary btn-flat">Agregar </a>
          </div>
        </div>
      </div>