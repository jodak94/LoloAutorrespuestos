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
        @include('pacientes::admin.pacientes.partials.create-fields')
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
      $("#add_cliente_button").on('click', function(){
        $("#error").hide();
        var razon_social = $("#razon_social").val();
        var ruc = $("#ruc").val();
        var telefono = $("#telefono").val();
        var direccion = $("#direccion").val();
        if(razon_social == '' || ruc == ''){
          $("#error").show();
          $("#error_message").html('Razon social y ruc son campos obligatorios');
          return;
        }
        $("#spin").show();
        $.ajax({
          url: "{{route('admin.pacientes.paciente.store_ajax')}}",
          type: 'POST',
          data: {
            'razon_social': razon_social,
            'ruc': ruc,
            'telefono': telefono,
            'direccion': direccion,
            "_token": "{{ csrf_token() }}",
          },
          success: function(data){
            if(data.error){
              $("#error").show();
              $("#error_message").html(data.message);
            }else{
              //Agregar datos a la cabecera de la venta
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
      $('#addClienteModalte').on('hidden.bs.modal', function () {
        $("#razon_social").val('')
        $("#ruc").val('')
        $("#telefono").val('')
        $("#direccion").val('')
      })
    });
  </script>
@endpush
