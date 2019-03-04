<script type="text/javascript">
  $( document ).ready(function(){
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
        url: '{!! route('admin.ventas.venta.index_ajax') !!}',
        type: "GET",
        data: function (d){
            d.razon_social = $("#razon_social").val();
            d.nro_factura = $("#nro_factura").val();
            d.fecha_desde = $("#fecha_desde").val();
            d.fecha_hasta = $("#fecha_hasta").val();
        },
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      },
      columns:[
        { data: 'created_at', name: 'created_at' },
        { data: 'nro_factura', name: 'nro_factura' },
        { data: 'razon_social', name: 'razon_social' },
        { data: 'monto_total', name: 'monto_total' },
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
    $("#razon_social").keyup(function(){
        table.ajax.reload();
    });
    $("#nro_factura").keyup(function(){
        table.ajax.reload();
    });
    $(".fecha").change(function(){
        table.ajax.reload();
    });
  })

</script>
