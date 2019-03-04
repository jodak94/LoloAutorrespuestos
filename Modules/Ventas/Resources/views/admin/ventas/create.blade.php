@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('ventas::ventas.title.create venta') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.ventas.venta.index') }}">{{ trans('ventas::ventas.title.ventas') }}</a></li>
        <li class="active">{{ trans('ventas::ventas.title.create venta') }}</li>
    </ol>
@stop
@push('css-stack')
  <link rel="stylesheet" href="{{ asset('themes/adminlte/css/vendor/jQueryUI/jquery-ui-1.10.3.custom.min.css') }}">
  <style>
    .input-error{
      background-color: #d73925;
      color: #fff;
    }
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{
      border: 1px solid #ddd
    }
    .center{
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    .tr-error{
      background-color: #dd4b39;
    }
    .tr-error:hover{
      background-color: #c9302c!important;
    }
  </style>
@endpush
@section('content')
    {!! Form::open(['route' => ['admin.ventas.venta.store'], 'method' => 'post', 'id' => 'venta-form']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                @include('partials.form-tab-headers')
                <div class="tab-content">
                    <?php $i = 0; ?>
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                        <?php $i++; ?>
                        <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                            @include('ventas::admin.ventas.partials.create-fields', ['lang' => $locale])
                        </div>
                    @endforeach

                    <div class="box-footer">
                      <button id="btnCrear" type="button" disabled class="btn btn-primary btn-flat" data-toggle="modal" data-target="#facturaModal">Crear Venta</button>
                      <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.ventas.venta.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                    </div>
                </div>
            </div> {{-- end nav-tabs-custom --}}
        </div>
    </div>
    @include('ventas::admin.ventas.partials.modal-crear-venta')
    {!! Form::close() !!}
@stop
@include('clientes::admin.clientes.partials.modal-add-cliente')
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
    <script type="text/javascript" src="{{ asset('themes/adminlte/js/vendor/jquery-ui-1.10.3.min.js') }}"></script>
    @include('ventas::admin.ventas.partials.script')
    @include('clientes::admin.datosfacturacions.partials.buscar-datos', ['create' => true])
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.ventas.venta.index') ?>" }
                ]
            });
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
@endpush
