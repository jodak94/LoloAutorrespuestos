<script type="text/javascript">
  $( document ).ready(function(){
    var table  = $('.data-table').DataTable({
      dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
        "<'row'<'col-xs-12't>>"+
        "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
      processing: false,
      serverSide: true,
      "ordering": false,
      "paginate": true,
      "lengthChange": true,
      "iDisplayLength": 50,
      "filter": true,
      "sort": true,
      "info": true,
      "autoWidth": true,
      "paginate": true,
      ajax:{
        url: '{!! route('admin.productos.producto.index_ajax') !!}',
        type: "GET",
        data: function (d){
            d.codigo = $("#codigo").val();
            d.nombre = $("#nombre").val();
        },
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      },
      columns:[
        { data: 'codigo', name: 'codigo' },
        { data: 'nombre', name: 'nombre' },
        { data: 'costo_format', name: 'costo_format' },
        { data: 'precio_format', name: 'precio_format' },
        { data: 'stock', name: 'stock' },
        { data: 'url_foto', name: 'url_foto' },
        { data: 'acciones', name: 'acciones' },
      ],
      columnDefs: [
        {targets: -1, className: 'center'},
      ],
      language: {
        processing:     "Procesando...",
        search:         "Buscar",
        lengthMenu:     "Mostrar _MENU_ Elementos",
        info:           "Mostrando de _START_ a _END_ registros de un total de _TOTAL_ registros",
        infoFiltered:   ".",
        infoPostFix:    "",
        loadingRecords: "Cargando Registros...",
        zeroRecords:    "No existen registros disponibles",
        emptyTable:     "No existen registros disponibles",
        paginate: {
          first:      "Primera",
          previous:   "Anterior",
          next:       "Siguiente",
          last:       "Ultima"
        }
      },
    });

    //filtros
    $("#codigo").keyup(function(){
        table.ajax.reload();
    });
    $("#nombre").keyup(function(){
        table.ajax.reload();
    });

  })

</script>
