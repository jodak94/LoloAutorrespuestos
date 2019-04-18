@extends('layouts.master')

@section('content-header')
    <h1>
        Informes año {{\Carbon\Carbon::now()->year}}
    </h1>
@stop

@section('styles')
  <style media="screen">
    .center{
      text-align: center;
    }
  </style>

@stop

@section('content')
  <!--Graficos-->
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Ventas del Año</h3>
          <a href="{{ route('admin.informes.informe.exportAnual') }}" class="btn btn-primary btn-flat pull-right" style="padding: 4px 10px; margin-right: 10px">
            <i class="fa fa-file-excel-o"></i> Exportar a Excel
          </a>
        </div>
        <div class="box-body">
          <div class="chart">
            <canvas id="ventas" height="230"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Ventas por Mes</h3>
          <div class="row">
            <div class="col-md-3">
              {!!Form::normalSelect('mes', 'Mes 1', $errors, $meses, (object)['mes' => $mes_actual - 1])!!}
            </div>
            <div class="col-md-3">
              <div class="form-group dropdown">
                <label for="mes2">Mes 2</label>
                <select name="mes2" class="form-control">
                  <option value="-1">--</option>
                  @foreach ($meses as $key => $mes)
                    @if($key == $mes_actual - 1)
                      <option disabled value="{{$key}}">{{$mes}}</option>
                    @else
                      <option value="{{$key}}">{{$mes}}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
            <canvas id="ventas_dia" height="230"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  @stop
@push('js-stack')
  {!! Theme::script('vendor/chartjs/Chart.min.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.date.js') !!}
  {!! Theme::script('vendor/pickadate/js/picker.time.js') !!}
  {!! Theme::script('vendor/jquery-ui/jquery-ui.min.js') !!}
  <script type="text/javascript">
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
  </script>
  @include('informes::admin.informes.partials.script-graficos')
@endpush
