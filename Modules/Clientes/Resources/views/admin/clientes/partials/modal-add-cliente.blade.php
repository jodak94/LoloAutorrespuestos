<div class="modal fade" id="addClienteModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cargar Nuevo Cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('clientes::admin.clientes.partials.create-fields')
        <div style="display:none" class="alert alert-danger alert-dismissible" role="alert" id="error">
          <span id="error_message"></span>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button id="add_cliente_button" type="button" class="btn btn-primary" style="float:left">
          <i class="fa fa-spinner fa-spin" aria-hidden="true" style="display:none"></i> Guardar
        </button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

@push('js-stack')
  <script>
    $( document ).ready(function() {
      var url;
      $("#add_cliente_button").on('click', function(){
        $("#error").hide();
        var razon_social = $("#razon_social").val();
        var ruc = $("#ruc").val();
        var telefono = $("#telefono").val();
        var direccion = $("#direccion").val();
        var datos_facturacion_id = $("#datos_id").val();
        if(razon_social == '' || ruc == ''){
          $("#error").show();
          $("#error_message").html('Razon social y ruc son campos obligatorios');
          return;
        }
        $("#spin").show();
        $.ajax({
          url: url,
          type: 'POST',
          data: {
            'razon_social': razon_social,
            'ruc': ruc,
            'telefono': telefono,
            'direccion': direccion,
            'datos_facturacion_id' : datos_facturacion_id,
            "_token": "{{ csrf_token() }}",
          },
          success: function(data){
            if(data.error){
              $("#error").show();
              $("#error_message").html(data.message);
            }else{
              $("#datos_razon_social").val(razon_social)
              $("#datos_ruc").val(ruc)
              $("#datos_telefono").val(telefono)
              $("#datos_direccion").val(direccion)
              $("#datosfacturacion").show()
              $("#buscar-datos").val("")
              $("#datos_id").val(data.datos.id)
              $("#addClienteModal").modal('hide');
            }
            $("#spin").hide();
          },
          error: function(error){
            $("#spin").hide();
            $("#error_message").html("Ocurrio un error inesperado");
          }
        })
      })
      $('#add-cliente-button').on('click', function () {
        $("#razon_social").val('')
        $("#ruc").val('')
        $("#telefono").val('')
        $("#direccion").val('')
        $("#addClienteModal").modal('show');
        url = "{{route('admin.clientes.datosfacturacion.store_ajax')}}"
      })

      $("#edit-cliente-button").on('click', function(){
        $("#razon_social").val($("#datos_razon_social").val())
        $("#ruc").val($("#datos_ruc").val())
        $("#telefono").val($("#datos_telefono").val())
        $("#direccion").val($("#datos_direccion").val())
        $("#addClienteModal").modal('show');
        url = "{{route('admin.clientes.datosfacturacion.update_ajax')}}"
      })
    });
  </script>
@endpush
