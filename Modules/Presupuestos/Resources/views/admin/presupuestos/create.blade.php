@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('presupuestos::presupuestos.title.create presupuesto') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.presupuestos.presupuesto.index') }}">{{ trans('presupuestos::presupuestos.title.presupuestos') }}</a></li>
        <li class="active">{{ trans('presupuestos::presupuestos.title.create presupuesto') }}</li>
    </ol>
@stop

@push('css-stack')
  <link rel="stylesheet" href="{{ asset('themes/adminlte/css/vendor/jQueryUI/jquery-ui-1.10.3.custom.min.css') }}">
@endpush

@section('content')
    {!! Form::open(['route' => ['admin.presupuestos.presupuesto.store'], 'method' => 'post']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                @include('partials.form-tab-headers')
                <div class="tab-content">
                    <?php $i = 0; ?>
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                        <?php $i++; ?>
                        <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                            @include('presupuestos::admin.presupuestos.partials.create-fields', ['lang' => $locale])
                        </div>
                    @endforeach

                    <div class="box-footer">
                        <button id="btn-crear" type="submit" class="btn btn-primary btn-flat">Crear y Generar Presupuesto</button>
                        <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.presupuestos.presupuesto.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
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
    <script type="text/javascript" src="{{ asset('themes/adminlte/js/vendor/jquery-ui-1.10.3.min.js') }}"></script>
    @include('presupuestos::admin.presupuestos.partials.script')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.presupuestos.presupuesto.index') ?>" }
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
