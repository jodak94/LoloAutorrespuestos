@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('productos::productos.title.productos') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('productos::productos.title.productos') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.productos.producto.export') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px; margin-right: 10px">
                      <i class="fa fa-file-excel-o"></i> Exportar a Excel
                    </a>
                    <a href="{{ route('admin.productos.producto.import') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px; margin-right: 10px">
                      <i class="fa fa-file-excel-o"></i> Importar desde Excel
                    </a>
                    <a href="{{ route('admin.productos.producto.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('productos::productos.button.create producto') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                  <div class="row">
                    <div class="col-md-3">
                      {!! Form::normalInput('codigo', 'Código', $errors) !!}
                    </div>
                    <div class="col-md-3">
                      {!! Form::normalInput('nombre', 'Nombre', $errors) !!}
                    </div>
                </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre del Producto</th>
                                <th>Costo</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Foto</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                              <th>Código</th>
                              <th>Nombre del Producto</th>
                              <th>Costo</th>
                              <th>Precio</th>
                              <th>Stock</th>
                              <th>Foto</th>
                              <th>Acciones</th>
                            </tfoot>
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
        <dd>{{ trans('productos::productos.title.create producto') }}</dd>
    </dl>
@stop

@push('js-stack')
    @include('productos::admin.productos.partials.script-index')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.productos.producto.create') ?>" }
                ]
            });
        });
    </script>
@endpush
