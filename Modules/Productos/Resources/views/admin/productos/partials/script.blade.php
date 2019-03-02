<script>
    $( document ).ready(function() {
      var url;
      $(".buscar-producto").autocomplete({
      source: '{{route('admin.productos.producto.search_ajax')}}',
      select: function( event, ui){
        $(this).closest('tr').find('.producto_id').val(ui.item.producto.id)
        $(this).closest('tr').find('.stock').val(ui.item.producto.stock)
        $(this).closest('tr').find('.cantidad').focus()
        $(this).closest('tr').find('.cantidad').removeAttr('readonly')
      },
    });
    $(".table").on('click', '.remove-field', function(){
      $(this).closest('tr').remove()
      set_disabled_to_btn_update()
    })

    function set_disabled_to_btn_update(){
      let ready = true;
      $('.cantidad').each(function(i){
        let cantidad = parseInt($(this).val())
        if (isNaN(cantidad)){
          ready = false;
          return;
        }
      })
      if(ready)
        $("#update-stock-button").removeAttr('disabled')
      else
        $("#update-stock-button").attr('disabled', true)
    }
    $(".table").on('keydown ', '.cantidad', function(event){
      if(event.keyCode == 13){
        let cantidad = parseInt($(this).val())
        event.preventDefault()
        if(!isNaN(cantidad)){
          if($(this).closest("tr").is(":last-child"))
            $("#add-entrada").click();
          $(this).closest('tr').next('tr').find('.buscar-producto').focus()
        }
      }
    })

    $(".table").on('keyup ', '.cantidad', function(event){
      set_disabled_to_btn_update()
    })

    var row =
         '<tr>'
        +'<td>'
        +'  <input class="buscar-producto form-control" placeholder="Buscar por nombre o código" required>'
        +'  <input type="hidden" class="producto_id" name="producto_id[]">'
        +'</td>'
        +'<td>'
        +'  <input class="form-control stock" readonly>'
        +'</td>'
        +'<td>'
        +'  <input type="number" class="form-control cantidad" name="cantidad[]" readonly required>'
        +'</td>'
        +'<td style="text-align:center;">'
        +'  <i class="glyphicon glyphicon-trash btn btn-danger remove-field">'
        +'</td>'
        +'</tr>'

    $("#add-entrada").on('click', function(){
      $("#btnUpdate").attr('disabled', true);
      $("#entradasBody").append(row)
      $(".buscar-producto").autocomplete({
        source: '{{route('admin.productos.producto.search_ajax')}}',
        select: function( event, ui){
        $(this).closest('tr').find('.producto_id').val(ui.item.producto.id)
        $(this).closest('tr').find('.stock').val(ui.item.producto.stock)
        $(this).closest('tr').find('.cantidad').focus()
        $(this).closest('tr').find('.cantidad').removeAttr('readonly')
        },
      });
    })
     
    });
  </script>
