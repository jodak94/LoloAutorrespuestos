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
  })
</script>
