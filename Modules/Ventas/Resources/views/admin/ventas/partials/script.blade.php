<script>
  $(document).ready(function(){
    $("#buscar-datos").autocomplete({
      source: '{{route('admin.clientes.datosfacturacion.search_ajax')}}',
      select: function( event, ui){
        $("#datos_id").val(ui.item.datos.id)
        $("#datos_razon_social").val(ui.item.datos.razon_social)
        $("#datos_ruc").val(ui.item.datos.ruc)
        $("#datos_telefono").val(ui.item.datos.telefono)
        $("#datos_direccion").val(ui.item.datos.direccion)
        $("#datosfacturacion").show()
        $("#razon_social").val(ui.item.datos.razon_social)
        $("#ruc").val(ui.item.datos.ruc)
        $("#telefono").val(ui.item.datos.telefono)
        $("#direccion").val(ui.item.datos.direccion)
      },
      close: function(event){
        $("#buscar-datos").val("")
      },
    })

    $(".buscar-producto").autocomplete({
      source: '{{route('admin.productos.producto.search_ajax')}}',
      select: function( event, ui){
        $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
        $(this).closest('tr').find('.iva').val('10%')
        $(this).closest('tr').find('.stock').val(ui.item.producto.  stock)
        $(this).closest('tr').find('.subtotal').val(0)
        $(this).closest('tr').find('.cantidad').removeAttr('readonly')
      },
    });


    $(".table").on('keydown ', '.cantidad', function(event){
      if(event.keyCode == 13){
        let cantidad = parseInt($(this).val())
        event.preventDefault()
        if(!isNaN(cantidad)){
          if($(this).closest("tr").is(":last-child"))
            $("#add-detalle").click();
          $(this).closest('tr').next('tr').find('.buscar-producto').focus()
        }
      }
    })

    $(".table").on('keyup ', '.cantidad', function(event){
      let cantidad = parseInt($(this).val())
      if (!isNaN(cantidad)) {
        $("#btnCrear").removeAttr('disabled');
        let subtotal = $(this).val() * $(this).closest('tr').find('.precio').val()
        $(this).closest('tr').find('.subtotal').val(subtotal)
        calculate_all()
      }else{
        $("#btnCrear").attr('disabled', true);
      }
    })

    $(".table").on('click', '.remove-field', function(){
      $(this).closest('tr').remove()
      calculate_all()
    })

    function calculate_all(){
      let total = 0;
      let totalIva10 = 0;
      $('.subtotal').each(function(i){
        total += parseFloat($(this).val());
      })
      $("#monto-total").val(total);
      $("#modal-monto-total").val(total);
      $("#total-iva-10").val(parseFloat(total / 11).toFixed(2))
    }

    $("select[name=tipo_factura]").change(function(){
      var forma_pago = $(this).val();
      if(forma_pago == 'credito'){
        $("#plazo_credito-1").show();
        $("#pago_cliente").val(0);
        $("#pago_cliente").attr('readonly', true);
      }else{
        $("#plazo_credito-1").hide();
        $("#pago_cliente").attr('readonly', false);
      }
    })

    $('#pago_cliente').on('keyup', function(){
      let val = $(this).val() - $("#modal-monto-total").val();
      $("#vuelto").val(val)
      if(val >= 0)
        $("#generar_factura").removeAttr('disabled')
      else
        $("#generar_factura").attr('disabled', true)
    })

    var row =
         '<tr>'
        +'<td>'
        +'  <input class="buscar-producto form-control" placeholder="Buscar por nombre" required>'
        +'  <input type="hidden" class="producto_id" name="producto_id[]">'
        +'</td>'
        +'<td>'
        +'  <input type="number" class="form-control cantidad" name="cantidad" required>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control iva" readonly>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control precio" name="precio[]" readonly>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control stock" readonly>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control subtotal" name="subtotal[]" readonly>'
        +'</td>'
        +'<td style="text-align:center;">'
        +'  <i class="glyphicon glyphicon-trash btn btn-danger remove-field">'
        +'</td>'
        +'</tr>'

    $("#add-detalle").on('click', function(){
      $("#btnCrear").attr('disabled', true);
      $("#detallesBody").append(row)
      $(".buscar-producto").autocomplete({
        source: '{{route('admin.productos.producto.search_ajax')}}',
        select: function( event, ui){
          $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
          $(this).closest('tr').find('.iva').val('10%')
          $(this).closest('tr').find('.stock').val(ui.item.producto.  stock)
          $(this).closest('tr').find('.subtotal').val(0)
        },
      });
    })
  })
</script>
