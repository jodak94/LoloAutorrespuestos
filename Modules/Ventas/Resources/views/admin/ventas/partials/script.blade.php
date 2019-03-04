<script>
  $(document).ready(function(){
    $(".buscar-producto").autocomplete({
      source: '{{route('admin.productos.producto.search_ajax')}}',
      select: function( event, ui){
        $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
        $(this).closest('tr').find('.iva').val('10%')
        $(this).closest('tr').find('.stock').val(ui.item.producto.stock)
        $(this).closest('tr').find('.subtotal').val(0)
        $(this).closest('tr').find('.cantidad').removeAttr('readonly')
        $(this).closest('tr').find('.producto_id').val(ui.item.producto.id)
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
        let subtotal = $(this).val() * $(this).closest('tr').find('.precio').val()
        $(this).closest('tr').find('.subtotal').val(subtotal)
        calculate_all()
        if(cantidad > $(this).closest('tr').find('.stock').val())
          $(this).closest('tr').addClass('tr-error');
        else
          $(this).closest('tr').removeClass('tr-error');
      }
      set_disabled_to_btn_crear()
    })

    $(".table").on('click', '.remove-field', function(){
      $(this).closest('tr').remove()
      calculate_all()
      set_disabled_to_btn_crear()
    })

    function set_disabled_to_btn_crear(){
      let ready = true;
      $('.cantidad').each(function(i){
        let cantidad = parseInt($(this).val())
        if (isNaN(cantidad)){
          ready = false;
          return;
        }
      })
      if(ready)
        $("#btnCrear").removeAttr('disabled')
      else
        $("#btnCrear").attr('disabled', true)
    }

    function calculate_all(){
      let total = 0;
      let totalIva10 = 0;
      let val = 0;
      $('.subtotal').each(function(i){
        val = parseFloat($(this).val());
        if(!isNaN(val))
          total += val;
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
        +'  <input type="number" class="form-control cantidad" name="cantidad[]" required>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control precio" name="precio_unitario[]" readonly>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control iva" readonly>'
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
