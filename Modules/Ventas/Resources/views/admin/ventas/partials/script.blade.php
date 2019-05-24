<script>
  $(document).ready(function(){
    var btn_crear = false
    @if(isset($edit) && $edit)
      $('.fecha').pickadate({
        monthsFull: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar',
        selectMonths: true,
        selectYears: 100,
        format:'dd/mm/yyyy'
      });
    @endif
    $("#generar_factura").on('ifChecked', function(e){
      $("#buscar-datos").removeAttr('disabled')
      $("#add-cliente-button").removeAttr('disabled')
      $("#nro_factura").val('{{$nro_factura}}')
      $("#btnCrear").attr('disabled', true)
      $("#guardar_parcial").attr('disabled', true)
    })
    $("#generar_factura").on('ifUnchecked', function(e){
      $("#buscar-datos").attr('disabled', 'disabled')
      $("#add-cliente-button").attr('disabled', 'disabled')
      limpiarDatosFacturacion()
      $("#datosfacturacion").hide()
      $("#nro_factura").val('xxx-xxx-xxxxxx')
      set_disabled_to_btn_crear()
    })
    $("#datos_id").on('change', function(){
      set_disabled_to_btn_crear()
    })

    function limpiarDatosFacturacion(){
      $("#datos_razon_social").val('')
      $("#datos_ruc").val('')
      $("#datos_telefono").val('')
      $("#datos_direccion").val('')
      $("#datos_id").val('')
      $("#buscar-datos").val('')
    }

    $(".precio_format").number( true , 0, ',', '.' );
    $(".precio_float_format").number( true , 3, ',', '.' );
    $(".buscar-producto").autocomplete({
      source: '{{route('admin.productos.producto.search_ajax')}}',
      select: function( event, ui){
        $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
        $(this).closest('tr').find('.foto').attr('src', ui.item.producto.url_foto)
        $(this).closest('tr').find('.stock').val(ui.item.producto.stock)
        $(this).closest('tr').find('.descuento').val(ui.item.producto.descuento)
        $(this).closest('tr').find('.subtotal').val(0)
        $(this).closest('tr').find('.cantidad').removeAttr('readonly')
        $(this).closest('tr').find('.cantidad').val(0)
        $(this).closest('tr').find('.producto_id').val(ui.item.producto.id)
      },
    });

    $("#buscar-datos").on("keydown", function(event){
      if(event.keyCode == 9){
        event.preventDefault();
        $("#detalles-table tr:last").find('.buscar-producto').focus()
      }
    })

    $(".table").on('keydown mouseup', '.cantidad', function(event){
      if(event.keyCode == 13){
        let cantidad = parseFloat($(this).val())
        event.preventDefault()
        if(!isNaN(cantidad)){
          if($(this).closest("tr").is(":last-child"))
            $("#add-detalle").click();
          $(this).closest('tr').next('tr').find('.buscar-producto').focus()
        }
      }
    })

    $(".table").on('change ', '.descuento', function(event){
      let precio = $(this).closest('tr').find('.precio').val();
      let cantidad = $(this).closest('tr').find('.cantidad').val()
      let subtotal = cantidad * precio * $(this).val()
      $(this).closest('tr').find('.subtotal').val(subtotal)
      calculate_all()
    })

    $(".table").on('keyup mouseup', '.cantidad', function(event){
      let cantidad = parseFloat($(this).val())
      if (!isNaN(cantidad)) {
        let precio = $(this).closest('tr').find('.precio').val();
        let descuento = $(this).closest('tr').find('.descuento').val()
        let subtotal = Math.round($(this).val() * precio * descuento)
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
        let cantidad = parseFloat($(this).val())
        if (isNaN(cantidad) || cantidad == 0){
          ready = false;
          return;
        }
      })
      if($("#generar_factura").prop('checked') && !$("#datos_id").val())
        ready = false;
      if(ready){
        $("#btnCrear").removeAttr('disabled')
        $("#guardar_parcial").removeAttr('disabled')
      }
      else{
        $("#btnCrear").attr('disabled', true)
        $("#guardar_parcial").attr('disabled', true)
      }
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
      $("#monto-total-letras").val(numeroALetras(total, { plural: 'GUARANIES.', singular: 'GUARANI.'}));
      @if(isset($actualizar) && $actualizar)
        $("#modal-monto-total").val(total - {{$venta->monto_pagado}});
      @else
        $("#modal-monto-total").val(total);
      @endif
      $("#total-iva-10").val(parseFloat(total / 11).toFixed(2))
    }

    $("select[name=tipo_factura]").change(function(){
      var forma_pago = $(this).val();
      if(forma_pago == 'credito'){
        // $("#plazo_credito-1").show();
        $("#vuelto_container").hide()
        $("#generar_venta").removeAttr('disabled')
        $("#monto_pagado").removeAttr('required')
        //$("#monto_pagado").val(0);
        //$("#monto_pagado").attr('readonly', true);
      }else{
        $("#vuelto_container").show()
        // $("#plazo_credito-1").hide();
        $("#monto_pagado").attr('required', 'required')
        $("#monto_pagado").attr('readonly', false);
      }
    })

    $('#monto_pagado').on('keyup', function(){
      checkVuelto()
    })

    function checkVuelto(){
      let val = $('#monto_pagado').val() - $("#modal-monto-total").val();
      $("#vuelto").val(val)
      if(!btn_crear)
        return
      if($("select[name=tipo_factura]").val() == 'credito'){
        $("#generar_venta").removeAttr('disabled')
        return
      }
      if(val >= 0)
        $("#generar_venta").removeAttr('disabled')
      else
        $("#generar_venta").attr('disabled', true)
    }

    $("#btnCrear").on('click', function(){
      @if(isset($actualizar) && $actualizar)
        $("#parcial").val(0);
      @endif
       btn_crear = true
      $("#generar_venta").attr('disabled', true)
      $("#modal-factura-container").show()
      $("#generar_venta").html('Guardar y Generar Factura')
      showVentaModal(false)
    })

    $("#guardar_parcial").on('click', function(){
      $("#generar_venta").removeAttr('disabled')
      btn_crear = false
      $("#modal-factura-container").hide()
      $("#generar_venta").html('Guardar Venta Parcial')
      showVentaModal(false)
    })

    // $("#guardar_parcial").on('click', function(){
    //   $("#modal-factura-container").hide()
    //   $("#generar_venta").html('Guardar Venta Parcial')
    //   // $("#venta-form").submit()
    //   showVentaModal(true)
    // })

    function showVentaModal(parcial){
      if($("#monto_pagado").val())
        checkVuelto()
      $("#facturaModal").modal('show');
    }

    var row =
         '<tr>'
        +'<td>'
        +'  <input class="buscar-producto form-control" placeholder="Buscar por nombre" required>'
        +'  <input type="hidden" class="producto_id" name="producto_id[]">'
        +'</td>'
        +'<td>'
        +'  <img class="foto" src='+"{{url('images/default-product.jpg')}}"+' width="40px" height="auto" style="display:flex; margin:auto">'
        +'</td>'
        +'<td>'
        +'  <input readonly type="number" class="form-control cantidad" name="cantidad[]" required>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control precio precio_format" name="precio_unitario[]" readonly>'
        +'</td>'
        +'<td>'
        +  '<select name="descuento[]" class="form-control descuento">'
          @foreach ($descuentos as $key => $descuento)
            + '<option value="'+'{{$key}}'+'">'+'{{$descuento}}'+'</option>'
          @endforeach
        +  '</select>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control stock" readonly>'
        +'</td>'
        +'<td>'
        +'  <input class="form-control subtotal precio_format" name="subtotal[]" readonly>'
        +'</td>'
        +'<td style="text-align:center;">'
        +'  <i class="glyphicon glyphicon-trash btn btn-danger remove-field">'
        +'</td>'
        +'</tr>'

    $("#add-detalle").on('click', function(){
      $("#btnCrear").attr('disabled', true);
      $("#guardar_parcial").attr('disabled', true);
      $("#detallesBody").append(row)
      $(".buscar-producto").autocomplete({
        source: '{{route('admin.productos.producto.search_ajax')}}',
        select: function( event, ui){
          $(this).closest('tr').find('.precio').val(ui.item.producto.precio)
          $(this).closest('tr').find('.foto').attr('src', ui.item.producto.url_foto)
          $(this).closest('tr').find('.stock').val(ui.item.producto.stock)
          $(this).closest('tr').find('.descuento').val(ui.item.producto.descuento)
          $(this).closest('tr').find('.subtotal').val(0)
          $(this).closest('tr').find('.cantidad').removeAttr('readonly')
          $(this).closest('tr').find('.producto_id').val(ui.item.producto.id)
          $(".precio_format").number( true , 0, ',', '.' );
        },
      });
    })

    $("#venta-form").submit(function(e) {
      console.log( $("#venta-form").serialize())
      e.preventDefault();
       $.ajax({
          type: 'post',
          url: $("#venta-form").attr("action"),
          data: $("#venta-form").serialize(),
          success: function(response) {
              if(response.generar_factura) {
                window.open('{{route("admin.ventas.venta.exportar")}}?format=pdf&download=false&venta_id='+response.venta_id,"_blank");
              }
              if(response.parcial)
                location.href = '{{route('admin.ventas.venta.index', ['parcial'])}}'
              else
                location.href = '{{route('admin.ventas.venta.index')}}'
           },
         });
    });

  })
</script>
