<script type="text/javascript">
$( document ).ready(function(){
    var totales_mes = <?php echo json_encode($totales_mes); ?>;
    var meses = <?php echo json_encode($meses); ?>;
    var ventas = document.getElementById("ventas").getContext("2d");
    var ventaChart = new Chart(ventas, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Total',
                data: totales_mes,
                backgroundColor:'rgba(255, 255, 255, 0.15)',
                borderColor: 'rgba(53, 124, 165, 1)',
                borderWidth: 3
            }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                  callback: function(label, index, labels) {
                      let n = new Intl.NumberFormat().format(label)
                      return n;
                  },
                  beginAtZero: true
              },
              scaleLabel: {
                  display: true,
                  labelString: 'Guaraníes'
              }
            }]
          },
          tooltips: {
           callbacks: {
               label: function(tooltipItem, data) {
                 let n = new Intl.NumberFormat().format(tooltipItem.yLabel)
                 return n + ' Gs.';
               }
           }
         }
        }
    });

    $("[name=mes]").on('change', function(){
      let mes = $(this).val();
      $('[name=mes2] option').removeAttr('disabled')
      $('[name=mes2] option[value="' + mes + '"]').attr('disabled', true)
      getPerDay(mes, 0, 'rgba(53, 124, 165, 1)')
    })
    $("[name=mes2]").on('change', function(){
      let mes = $(this).val();
      if(mes == -1){
        $('[name=mes] option').removeAttr('disabled')
        ventaDiaChart.data.datasets.pop();
        ventaDiaChart.update();
      }else{
        $('[name=mes] option').removeAttr('disabled')
        $('[name=mes] option[value="' + mes + '"]').attr('disabled', true)
        getPerDay(mes, 1, 'rgba(168, 6, 21, 1)')
      }
    })

    function getPerDay(mes, index, borderColor){
      $.ajax({
        url: '{{route('admin.informes.informe.perDayAjax')}}',
        type: 'GET',
        data: {
          'mes': mes,
        },
        success: function(data){
          if(!data.error){
            let dataset = {
                label: data.mes,
                data: data.total_dias,
                backgroundColor:'rgba(255, 255, 255, 0.15)',
                borderColor: borderColor,
                borderWidth: 3
            }
            ventaDiaChart.data.datasets[index] = dataset;
            if(ventaDiaChart.data.datasets.length == 1)
              ventaDiaChart.data.labels = data.dias;
            else
              if(data.dias.length > ventaDiaChart.data.labels.length)
                ventaDiaChart.data.labels = data.dias;
            ventaDiaChart.update();
          }
        },
      })
    }

    var total_dias = <?php echo json_encode($total_dias); ?>;
    var dias = <?php echo json_encode($dias); ?>;
    var dias_label = '{{$meses[$mes_actual - 1]}}'
    var ventas_dia = document.getElementById("ventas_dia").getContext("2d");
    var ventaDiaChart = new Chart(ventas_dia, {
        type: 'line',
        data: {
            labels: dias,
            datasets: [{
                label: dias_label,
                data: total_dias,
                backgroundColor:'rgba(255, 255, 255, 0.15)',
                borderColor: 'rgba(53, 124, 165, 1)',
                borderWidth: 3
            }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                  callback: function(label, index, labels) {
                      let n = new Intl.NumberFormat().format(label)
                      return n;
                  },
                  beginAtZero: true
              },
              scaleLabel: {
                  display: true,
                  labelString: 'Guaraníes'
              }
            }]
          },
          tooltips: {
           callbacks: {
               label: function(tooltipItem, data) {
                 let n = new Intl.NumberFormat().format(tooltipItem.yLabel)
                 return n + ' Gs.';
               }
           }
         }
        }
    });
});

</script>
