@if (Session::has('success'))
  <script>
    $(document).on('ready', function(){
      $.toast({
        heading: 'Operación exitosa',
        text: '{{ Session::get('success') }}',
        showHideTransition: 'slide',
        icon:'success',
        position: 'top-right'
      })
    })
  </script>
@endif

@if (Session::has('error'))
  <script>
    $(document).on('ready', function(){
      $.toast({
        heading: 'Error',
        text: '{{ Session::get('error') }}',
        showHideTransition: 'slide',
        icon:'error',
        position: 'top-right'
      })
    })
  </script>
@endif

@if (Session::has('warning'))
  <script>
    $(document).on('ready', function(){
      $.toast({
        heading: 'Atención  ',
        text: '{{ Session::get('warning') }}',
        showHideTransition: 'slide',
        icon:'warning',
        position: 'top-right'
      })
    })
  </script>
@endif
