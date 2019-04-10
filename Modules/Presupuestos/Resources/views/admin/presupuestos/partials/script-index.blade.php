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
        url: '{!! route('admin.presupuestos.presupuesto.index_ajax') !!}',
        type: "GET",
        data: function (d){
            d.nombre_cliente = $("#nombre_cliente").val();
            d.nro_presupuesto = $("#nro_presupuesto").val();
            d.fecha_desde = $("#fecha_desde").val();
            d.fecha_hasta = $("#fecha_hasta").val();
        },
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      },
      columns:[
        { data: 'created_at', name: 'created_at' },
        { data: 'nro_presupuesto', name: 'nro_presupuesto' },
        { data: 'nombre_cliente', name: 'nombre_cliente' },
        { data: 'precio_total', name: 'precio_total' },
        { data: 'acciones', name: 'acciones' },
      ],
      columnDefs: [
        {targets: -1, className: 'center'},
      ],
      /*drawCallback: function() {
          $(".detalle").click(function() {
            console.log($(this).attr('id'))
          })
      },*/
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

     $('.data-table').on('click','.detalle',function() {
         var presupuesto_id = $(this).attr("id").replace("detalle_","");
         $.confirm({
            title: 'Vista Previa',
            boxWidth: '80%',
            useBootstrap: false,
            closeIcon: true,
            escapeKey: true,
            backgroundDismiss: true,
            draggable: false,
            content: 'url:{{route("admin.presupuestos.presupuesto.exportar")}}?format=html&download=false&presupuesto_id='+presupuesto_id,
            buttons: {
                descargar: function() {
                    location.href = '{{route("admin.presupuestos.presupuesto.exportar")}}?format=pdf&download=true&presupuesto_id='+presupuesto_id
                }
            }
        });
     });
    
    //filtros
    $("#nombre_cliente").keyup(function(){
        table.ajax.reload();
    });
    $("#nro_presupuesto").keyup(function(){
        table.ajax.reload();
    });
    $(".fecha").change(function(){
        table.ajax.reload();
    });
  })

</script>
