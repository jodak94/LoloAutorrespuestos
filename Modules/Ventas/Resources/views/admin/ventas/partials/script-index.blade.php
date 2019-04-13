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
            d.credito = '{{$credito}}'
        },
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
      },
      "drawCallback": function (data) {
        var response = data.json;
        $("#suma").html(response.suma);
      },
      columns:[
        { data: 'created_at', name: 'created_at' },
        { data: 'nro_factura', name: 'nro_factura', className: 'nro_factura' },
        { data: 'razon_social', name: 'razon_social', className: 'razon_social' },
        { data: 'monto_total', name: 'monto_total', className: 'monto_total' },
        @if($credito)
          { data: 'monto_pagado', name: 'monto_pagado', className: 'monto_pagado' },
        @endif
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
    @if($credito)
      $('.data-table').on('click','.pagar',function() {
        let factura = $(this).closest('tr').find('.nro_factura').html()
        let monto_total = $(this).closest('tr').find('.monto_total').html()
        let monto_pagado = $(this).closest('tr').find('.monto_pagado').html()
        let venta_id = $(this).attr('venta')
        monto_total = monto_total.replace(/\./g, '').replace(',', '.')
        monto_pagado = monto_pagado.replace(/\./g, '').replace(',', '.')
        let deuda = monto_total - monto_pagado
        let html =
         '<div class="row" style="width:100%">'
        +'  <div class="col-md-4">'
        +'    <div class="form-group ">'
        +'      <label>Total</label>'
        +'      <input readonly class="form-control pago_format" value="'+monto_total+'">'
        +'    </div>'
        +'  </div>'
        +'  <div class="col-md-4">'
        +'    <div class="form-group ">'
        +'      <label>Pagado</label>'
        +'      <input id="pagado_modal" readonly class="form-control pago_format" value="'+monto_pagado+'">'
        +'    </div>'
        +'  </div>'
        +'  <div class="col-md-4">'
        +'    <div class="form-group ">'
        +'      <label>Deuda</label>'
        +'      <input id="deuda_modal" readonly class="form-control pago_format" value="'+deuda+'">'
        +'    </div>'
        +'  </div>'
        +'  <div class="col-md-3">'
        +'    <div class="form-group ">'
        +'      <label>Monto a pagar</label>'
        +'      <input id="a_pagar" class="form-control pago_format">'
        +'    </div>'
        +'  </div>'
        +'</div>'
        $.confirm({
           title: 'Realizar Pago - Nro. Factura: ' + factura,
           boxWidth: '80%',
           useBootstrap: false,
           escapeKey: true,
           backgroundDismiss: true,
           draggable: false,
           content: html,
           buttons: {
               aceptar: function() {
                 $.ajax({
                   url: '{{route('admin.ventas.venta.pago_credito')}}',
                   type: 'POST',
                   data: {
                     'venta_id': venta_id,
                     'monto_pagado': $("#pagado_modal").val(),
                     "_token": "{{ csrf_token() }}",
                   },
                   success: function(data){
                     let heading;
                     let icon;
                     if(data.error){
                       heading = 'Error';
                       icon = 'error';
                     }else{
                       heading = 'Operación exitosa';
                       icon = 'success'
                     }
                     $.toast({
                       heading: heading,
                       text: data.message,
                       showHideTransition: 'slide',
                       icon:icon,
                       position: 'top-right'
                     })
                     table.ajax.reload();
                   },
                   error: function(error){
                     console.log(error)
                     $.toast({
                       heading: 'Error',
                       text: 'No se puede conectar con el servidor',
                       showHideTransition: 'slide',
                       icon:'error',
                       position: 'top-right'
                     })
                   }
                 })
               }
           },
           onContentReady: function(){
             $(".pago_format").number( true , 0, ',', '.' );
             $("#a_pagar").on('keyup', function(){
               if($("#a_pagar").val() != '' && $("#a_pagar").val() != undefined){
                 let pagado_ = parseInt(monto_pagado) + parseInt($(this).val())
                 let deuda_ = parseInt(deuda) - parseInt($(this).val())
                 $("#deuda_modal").val(deuda_)
                 $("#pagado_modal").val(pagado_)
                }
             })
           }
       });
     });
    @endif
  })

</script>
