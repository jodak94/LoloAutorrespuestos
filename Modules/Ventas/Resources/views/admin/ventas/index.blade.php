@extends('layouts.master')

@section('content-header')
    <h1>
        @if($credito)
          Ventas a Crédito
        @else
          {{ trans('ventas::ventas.title.ventas') }}
        @endif
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('ventas::ventas.title.ventas') }}</li>
    </ol>
@stop
@push('css-stack')
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
      .suma{
        color: #DC3601;
        font-size: 25px;
        font-weight: 200;
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
                  <div class="row">
                    <div class="col-md-3">
                      {!! Form:: normalSelect('tipo_factura', 'Tipo de Factura', $errors, $tipos_factura) !!}
                    </div>
                    <div class="col-md-9">
                      <span class="pull-right suma">Total ventas: <span  id="suma"></span> Gs.</span>
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
                                  @if($credito)
                                    <th>Monto Pagado</th>
                                  @endif
                                  <th>Tipo de Factura</th>
                                  <th data-sortable="false">Acciones</th>
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
                                  @if($credito)
                                    <th>Monto Pagado</th>
                                  @endif
                                  <th>Tipo de Factura</th>
                                  <th>Acciones</th>
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
  <script src="{{ asset('js/jquery.number.min.js') }}"></script>
  {!! Theme::script('vendor/pickadate/js/picker.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.date.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.time.js') !!}

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
