@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('productos::productos.title.create producto') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.productos.producto.index') }}">{{ trans('productos::productos.title.productos') }}</a></li>
        <li class="active">{{ trans('productos::productos.title.create producto') }}</li>
    </ol>
@stop

@section('content')
    {!! Form::open(['route' => ['admin.productos.producto.store'], 'method' => 'post', 'files' => true]) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                @include('partials.form-tab-headers')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="tab-content">
                    <?php $i = 0; ?>
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                        <?php $i++; ?>
                        <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                            @include('productos::admin.productos.partials.create-fields', ['lang' => $locale])
                        </div>
                    @endforeach

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.create') }}</button>
                        <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.productos.producto.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                    </div>
                </div>
            </div> {{-- end nav-tabs-custom --}}
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index') }}</dd>
    </dl>
@stop

@push('js-stack')
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $(".precio").number( true , 0, ',', '.' );
            $(".costo").number( true , 0, ',', '.' );
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.productos.producto.index') ?>" }
                ]
            });

            $("#nombre").on('keyup', function(){
              if($(this).val().length > 1)
                $("#generar-codigo").removeAttr('disabled');
              else
                $("#generar-codigo").attr('disabled', true)
            })

            $("#generar-codigo").on('click', function(){
              $.ajax({
                type: 'GET',
                url: '{{route('admin.productos.producto.generate_code')}}',
                data: {nombre: $("#nombre").val()},
                success: function(data){
                  if(!data.error)
                    $("#codigo").val(data.codigo);
                  else
                    $.toast({
                      heading: 'Eror',
                      text: data.message,
                      showHideTransition: 'slide',
                      icon:'error',
                      position: 'top-right'
                    })
                }
              })
            })
        });
    </script>
    <script>
        $( document ).ready(function() {
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
        });
    </script>
    <script>
        $( document ).ready(function() {
            $("#img-input").change(function() {
                readURL(this);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#preview').css('display','block');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>
@endpush
