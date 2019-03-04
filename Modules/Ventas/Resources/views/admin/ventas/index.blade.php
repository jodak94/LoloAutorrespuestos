@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('ventas::ventas.title.ventas') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('ventas::ventas.title.ventas') }}</li>
    </ol>
@stop
@push('css-stack')
    <link rel="stylesheet" href="{{ asset('themes/adminlte/css/vendor/jQueryUI/jquery-ui-1.10.3.custom.min.css') }}">
    {!! Theme::style('vendor/pickadate/css/classic.css') !!}
    {!! Theme::style('vendor/pickadate/css/classic.date.css') !!}
    {!! Theme::style('vendor/pickadate/css/classic.time.css') !!}
    <style>
      .input-error{
        background-color: #d73925;
        color: #fff;
      }
      .picker__select--year{
        padding: 1px;
      }
      .picker__select--month{
        padding: 1px;
      }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.ventas.venta.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('ventas::ventas.button.create venta') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                    <div class="row">
                      <div class="col-md-3">
                        {!! Form::normalInput('fecha_desde', 'Fecha desde', $errors,(object)['fecha_desde'=>$today],['class'=>'form-control fecha','id'=>'fecha_desde']) !!}
                      </div>
                      <div class="col-md-3">
                        {!! Form::normalInput('fecha_hasta', 'Fecha hasta', $errors,(object)['fecha_hasta'=>$today],['class'=>'form-control fecha','id'=>'fecha_hasta']) !!}
                      </div>
                      <div class="col-md-3">
                        {!! Form::normalInput('nro_factura', 'N° Factura', $errors,null,['class'=>'form-control']) !!}
                      </div>
                      <div class="col-md-3">
                        {!! Form::normalInput('razon_social', 'Razón Social', $errors,null,['placeholder' => 'Ingresar Ruc o Razón social', 'type'=>'text']) !!}
                      </div>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
                              <tr>
                                  <th>Fecha</th>
                                  <th>N° Factura</th>
                                  <th>Cliente</th>
                                  <th>Monto Total</th>
                                  <th data-sortable="false">Detalles</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                              <tr>
                                  <th>Fecha</th>
                                  <th>N° Factura</th>
                                  <th>Cliente</th>
                                  <th>Monto Total</th>
                                  <th>Detalles</th>
                              </tr>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('ventas::ventas.title.create venta') }}</dd>
    </dl>
@stop

@push('js-stack')
  {!! Theme::script('vendor/pickadate/js/picker.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.date.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.time.js') !!}
  <script type="text/javascript" src="{{ asset('themes/adminlte/js/vendor/jquery-ui-1.10.3.min.js') }}"></script>
  @include('ventas::admin.ventas.partials.script-index')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.ventas.venta.create') ?>" }
                ]
            });
        });
    </script>
    <?php $locale = locale(); ?>
@endpush
