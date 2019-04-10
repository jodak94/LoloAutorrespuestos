<script>
  $(document).ready(function(){

    $("form").submit(function(e) {
      e.preventDefault();
       $.ajax({
          type: 'POST',
          url: $("form").attr("action"),
          data: $("form").serialize(), 
          success: function(response) {
              window.open('{{route("admin.presupuestos.presupuesto.exportar")}}?format=pdf&download=false&presupuesto_id='+response.presupuesto_id,"_blank");
              location.href = '{{route('admin.presupuestos.presupuesto.index')}}' 
           },
});
    });

    $(".buscar-producto").autocomplete({
      source: '{{route('admin.productos.producto.search_ajax')}}',
      select: function( event, ui){
        $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
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
        let subtotal = $(this).val() * $(this).closest('tr').find('.precio').val() * $(this).closest('tr').find('.descuento').val()
        $(this).closest('tr').find('.subtotal').val(Math.round(subtotal))
        calculate_all()
        if(cantidad > $(this).closest('tr').find('.stock').val())
          $(this).closest('tr').addClass('tr-error');
        else
          $(this).closest('tr').removeClass('tr-error');
      }
      set_disabled_to_btn_crear()
    })

    $(".table").on('change ', '.descuento', function(event){
      let precio = $(this).closest('tr').find('.precio').val();
      let cantidad = $(this).closest('tr').find('.cantidad').val()
      let subtotal = cantidad * precio * $(this).val()
      $(this).closest('tr').find('.subtotal').val(Math.round(subtotal))
      calculate_all()
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
        $("#btn-crear").removeAttr('disabled')
      else
        $("#btn-crear").attr('disabled', true)
    }

    function calculate_all(){
      let total = 0;
      let subtotal = 0;
      let val = 0;
      $('.subtotal').each(function(i){
        val = parseFloat($(this).val());
        if(!isNaN(val))
          total += val;
      })
      $("#total").val(Math.round(total));
      $("#sub-total").val(Math.round(total));
    }


    var row =
         '<tr>'
        +'<td>'
        +'  <input class="buscar-producto form-control" required>'
        +'  <input type="hidden" class="producto_id" name="producto_id[]">'
        +'</td>'
        +'<td>'
        +'  <input type="number" class="form-control cantidad" name="cantidad[]" required>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control precio" name="precio_unitario[]" readonly>'
        +'</td>'
         +'<td>'
        +  '<select name="descuento[]" class="form-control descuento">'
          @foreach ($descuentos as $key => $descuento)
            + '<option value="'+'{{$key}}'+'">'+'{{$descuento}}'+'</option>'
          @endforeach
        +  '</select>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control subtotal" name="total[]" readonly>'
        +'</td>'
        +'<td style="text-align:center;">'
        +'  <i class="glyphicon glyphicon-trash btn btn-danger remove-field">'
        +'</td>'
        +'</tr>'

    $("#add-detalle").on('click', function(){
      $("#btn-crear").attr('disabled', true);
      $("#detallesBody").append(row)
      $(".buscar-producto").autocomplete({
        source: '{{route('admin.productos.producto.search_ajax')}}',
        select: function( event, ui){
        $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
        $(this).closest('tr').find('.subtotal').val(0)
        $(this).closest('tr').find('.cantidad').removeAttr('readonly')
        $(this).closest('tr').find('.producto_id').val(ui.item.producto.id)
        },
      });
    })

  })
</script>
