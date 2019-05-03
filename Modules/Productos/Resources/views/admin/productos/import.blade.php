@extends('layouts.master')

@section('content-header')
    <h1>
        Importar Productos
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.productos.producto.index') }}">{{ trans('productos::productos.title.productos') }}</a></li>
        <li class="active">{{ trans('productos::productos.title.create producto') }}</li>
    </ol>
@stop

@push('css-stack')
{!! Theme::style('vendor/jquery-confirm/jquery-confirm.min.css') !!}
  <style>
    .has-error {
        border-color:#d43f3a;
    }
  </style>
@endpush

@section('content')
    {!! Form::open(['route' => ['admin.productos.producto.import_productos'], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                @include('partials.form-tab-headers')
                <div class="tab-content">
                    <?php $i = 0; ?>
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                        <?php $i++; ?>
                        <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                            @include('productos::admin.productos.partials.import', ['lang' => $locale])
                        </div>
                    @endforeach
                    <p>En caso de productos con errores, se listaran en esta tabla</p>
                    <table id="table-errors" class="data-table table table-bordered table-hover dataTable no-footer">
                        <thead>
                            <th>
                                <td> Código</td>
                                <td> Nombre</td>
                                <td> Descripción</td>
                                <td> Stock</td>
                                <td> Stock Crítico</td>
                                <td> Precio</td>
                                <td> Costo </td>
                                <td> Descuento </td>
                                <td> </td>
                            </th>
                        </thead>
                        <tbody>
                            <tr id="sin-datos">
                                <td colspan="6" style="text-align:center"> No se encontraron productos con errores</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="box-footer">
                        <button id ="btn-guardar" type="button" class="btn btn-primary btn-flat" style="display:none" disabled>Guardar Productos</button>
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
    {!! Theme::script('vendor/jquery-tabledit/jquery.tabledit.min.js') !!}
    {!! Theme::script('vendor/jquery-confirm/jquery-confirm.min.js') !!}
    <script src="{{ asset('js/jquery.number.min.js') }}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            var productos;
            var uploaded_files= [];
            $("form").submit(function(e) {
                e.preventDefault();
                var form = $('form');
                var data = new FormData(form[0])

                $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                url: $("form").attr("action"),
                success: function(response) {
                    $("#btn-subir").attr("disabled",true);
                    uploaded_files.push(document.querySelector('input[type=file]').files[0].name);
                    productos= response.productos;
                    for(let index in response.productos) {
                        let producto = response.productos[index];
                        let errores = response.errores[index][0];
                        $("#sin-datos").hide();
                        let row = "<tr id='"+index+"'><td></td><td> <input type='text' data-key='codigo'  value='"+(producto.codigo!== null?producto.codigo:'')+"' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.codigo !== undefined?errores.codigo:'')+"' class='form-control "+(errores.codigo !== undefined?'has-error' : '')+"'></td>" +
                        "<td> <input type='text'  value='"+(producto.nombre !== null?producto.nombre:'')+"' data-key='nombre' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.nombre !== undefined?errores.nombre:'')+"' class='form-control "+(errores.nombre !== undefined?'has-error' : '')+"'></td>"+
                        "<td> <input type='text'  value='"+(producto.descripcion !== null?producto.descripcion:'')+"' data-key='descripcion' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.descripcion !== undefined?errores.descripcion:'')+"' class='form-control "+(errores.descripcion !== undefined?'has-error' : '')+"'></td>"+
                        "<td> <input type='number'  value='"+(producto.stock!== null?producto.stock:'')+"' data-key='stock' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.stock !== undefined?errores.stock:'')+"' class='form-control "+(errores.stock !== undefined?'has-error' : '')+"'></td>"+
                        "<td> <input type='number'  value='"+(producto.stock_critico!== null?producto.stock_critico:'')+"' data-key='stock_critico' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.stock_critico !== undefined?errores.stock_critico:'')+"' class='form-control "+(errores.stock_critico !== undefined?'has-error' : '')+"'></td>"+
                        "<td> <input type='number'  value='"+(producto.precio!== null?producto.precio:'')+"' data-key='precio' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.precio !== undefined?errores.precio:'')+"' class='form-control "+(errores.precio !== undefined?'has-error' : '')+"'></td>"+
                         "<td> <input type='number'  value='"+(producto.costo!== null?producto.costo:'')+"' data-key='costo' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.costo !== undefined?errores.costo:'')+"' class='form-control "+(errores.costo !== undefined?'has-error' : '')+"'></td>"+
                         "<td> <input type='number'  value='"+(producto.descuento!== null?producto.descuento:'')+"' data-key='descuento' data-toggle='popover' data-placement='top' data-trigger='hover' data-content='"+(errores.descuento !== undefined?errores.descuento:'')+"' class='form-control "+(errores.descuento !== undefined?'has-error' : '')+"'></td>"+
                        "<td style='text-align:center;'><i class='glyphicon glyphicon-trash btn btn-danger remove-field'></td>"+
                        "</tr>";
                        $("#table-errors").find('tbody').append(row);
                        $("#table-errors").trigger("new_row",[index]);
                    }
                    $("#btn-guardar").show();
                    $("[data-toggle=popover]").popover();
                    if(response.productos.length > 0 && response.cargados > 0) {
                        $.alert({
                        type: 'orange',
                        title: 'Atención',
                        content: 'Se han cargado '+response.cargados+' productos nuevos. Pero se encontraron '+response.productos.length+' productos con errores',
                    });
                    }else if(response.productos.length > 0 && response.cargados == 0) {
                        $.alert({
                        type: 'red',
                        title: 'Productos con Errores',
                        content: 'No se cargo ningun producto nuevo. Por favor verifiquelos y vuelva a intentarlo',
                        });
                    }else if(response.productos.length == 0 && response.cargados > 0) {
                        $.alert({
                        type: 'green',
                        title: 'Productos Cargados',
                        content: 'Se han cargado '+response.cargados+' productos nuevos.',
                        onClose: function() {
                            location.href = "{{ route('admin.productos.producto.index') }}"
                        }
                        });
                    }

           },
           error: function(error) {
                $.alert({
                        type: 'red',
                        title: 'Error',
                        content: 'El archivo debe estar en formato .xls o .xlsx (Excel)',
                        });
           }
});
    });
    $("#table-errors").on('new_row',function(event,row_id) {
        event.preventDefault();
        console.log(row_id);
        $("#"+row_id).find("input").bind('input',function() {
                            let index = $(this).parents('tr').attr('id');
                            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                    }
                });
                            let producto = {
                                codigo: $("#"+row_id).find('[data-key=codigo]').val(),
                                nombre: $("#"+row_id).find('[data-key=nombre]').val(),
                                descripcion: $("#"+row_id).find('[data-key=descripcion]').val(),
                                stock: $("#"+row_id).find('[data-key=stock]').val(),
                                stock_critico: $("#"+row_id).find('[data-key=stock_critico]').val(),
                                precio: $("#"+row_id).find('[data-key=precio]').val(),
                                costo: $("#"+row_id).find('[data-key=costo]').val(),
                                descuento: $("#"+row_id).find('[data-key=descuento]').val(),
                            }
                            productos[row_id] = producto;
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.productos.producto.validation')}}",
                                contentType: 'application/json',
                                dataType: 'json',
                                data: JSON.stringify({producto:producto}),
                                success: function(res) {
                                    $("#"+row_id).find('.has-error').removeClass('has-error');
                                    $("#"+row_id).find('.has-error').popover("disable");
                                },
                                error: function(error) {console.log("error",JSON.parse(error.responseText));
                                    let errorObj = JSON.parse(error.responseText).error;
                                    $("#"+row_id).find('.has-error').popover("disable");
                                    $("#"+row_id).find('.has-error').removeClass('has-error');
                                    for(let key in errorObj){
                                        $("#"+row_id).find('[data-key='+key+']').addClass('has-error');
                                         $("#"+row_id).find('[data-key='+key+']').attr('data-content',errorObj[key]);
                                         $("#"+row_id).find('[data-key='+key+']').popover('enable');
                                    }

                                },
                                complete: function() {
                                    if($('.has-error').length == 0) {
                                        $("#btn-guardar").attr("disabled",false);
                                    }else {
                                        $("#btn-guardar").attr("disabled",true);
                                    }
                                }
                            })

                        })
    })

     $("#table-errors").on('click', '.remove-field', function(){
      delete productos[$(this).closest('tr').attr('id')]
      console.log(productos)
      $(this).closest('tr').remove()
    })

    $("#btn-guardar").click(function() {
        if($('.has-error').length == 0) {
            $("#btn-guardar").attr("disabled",false);
            $.ajax({
                                type: 'POST',
                                url: "{{ route('admin.productos.producto.store_ajax')}}",
                                contentType: 'application/json',
                                dataType: 'json',
                                data: JSON.stringify({productos:productos}),
                                success: function(res) {
                                    console.log("Productos Guardados con éxito");
                                     $.alert({
                                        type: 'green',
                                        title: 'Productos Cargados',
                                        content: 'Se han cargado '+res.cargados+' productos nuevos.',
                                        onClose: function() {
                                            location.href = "{{ route('admin.productos.producto.index') }}"
                                        }
                        });
                                },
                                error: function(error) {
                                    console.log("error al guardar productos");

                                },
                            })
        }else {
            $("#btn-guardar").attr("disabled",true);
        }

    })

            $("#file").change(function(e){
                if(uploaded_files.length > 0) {
                    if(uploaded_files.indexOf(e.target.files[0].name) == -1) {
                        $("#btn-subir").attr("disabled",false);
                    }else {
                        $("#btn-subir").attr("disabled",true);
                    }
                }
            });
            $(".precio").number( true , 0, ',', '.' );
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.productos.producto.index') ?>" }
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
