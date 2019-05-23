<div class="modal fade" id="facturaModal" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background-color: #3c8dbc; color: #ffffff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h4 class="modal-title"><strong>Crear Venta</strong></h4></center>
        </div><br>
        <div class="modal-body">
           <div class="row" id="modal-factura-container">
              <div class="col-sm-6">
                 @if(isset($edit) && $edit)
                   {!! Form:: normalSelect('tipo_factura', 'Tipo de Factura:', $errors, $tipos_factura, $factura) !!}
                 @else
                   {!! Form:: normalSelect('tipo_factura', 'Tipo de Factura:', $errors, $tipos_factura) !!}
                 @endif
              </div>
              @if(isset($actualizar) && $actualizar)
                <div class="col-sm-6">
                  {!! Form::normalInput('nro_factura', 'Nro. de Factura', $errors, (object)['nro_factura' => $nro_factura]) !!}
                </div>
              @endif
           </div>
           @if((isset($edit) && $edit) || (isset($actualizar) && $actualizar))
             @php
               if(isset($actualizar) && $actualizar)
                $factura = $venta;
              else {
                $actualizar = false;
              }
             @endphp
             {!! Form::normalInput('modal-monto-total', $actualizar?'Total a Pagar (Deuda):':'Total a Pagar:', $errors ,(object)['modal-monto-total' => $actualizar?0:$factura->monto_total], ['readonly'=>'', 'class' => 'form-control precio_format'] ) !!}
           @else
             {!! Form::normalInput('modal-monto-total', 'Total a Pagar:', $errors ,(object)['modal-monto-total' => 0], ['readonly'=>'', 'class' => 'form-control precio_format'] ) !!}
           @endif
           @if(isset($factura)   && $factura->tipo_factura == 'credito')
             {!! Form::normalInput('monto_pagado', 'Pago del Cliente:', $errors ,(object)['pago_cliente' => ''], ['autofocus', 'class' => 'form-control precio_format'] ) !!}
             <div id="vuelto_container" style="display:none">
               {!! Form::normalInput('vuelto_cliente', 'Vuelto:', $errors ,null, ['id'=>'vuelto','readonly'=>'', 'class' => 'form-control precio_format'] ) !!}
             </div>
           @else
             @php
               $required = true;
               if((isset($actualizar) && $actualizar) || (isset($parcial) && $parcial))
                $required = false;
             @endphp
             {!! Form::normalInput('monto_pagado', 'Pago del Cliente:', $errors ,(object)['pago_cliente' => ''], ['required'=>$required,'autofocus', 'class' => 'form-control precio_format'] ) !!}
             <div id="vuelto_container">
               {!! Form::normalInput('vuelto_cliente', 'Vuelto:', $errors ,null, ['id'=>'vuelto','readonly'=>'', 'class' => 'form-control precio_format'] ) !!}
             </div>
           @endif

           <div id="plazo_credito-1" style="display:none">
              {{-- {!! Form:: normalSelect('plazo_credito', 'Plazo del Crédito:', $errors, [''=>'---','5'=>'5 días','10'=>'10 días','30'=>'30 días','60'=>'60 días','90'=>'90 días'],null,['id'=>'plazo_credito']) !!} --}}
           </div>
           <div class="modal-body">
              <div class="box-footer">
                  <div class="modal-footer">
                      @if((isset($factura)  && $factura->tipo_factura == 'credito') || (isset($parcial) && $parcial) || (isset($actualizar) && $actualizar))
                        <button type="submit" class="btn btn-primary btn-flat" id="generar_venta">Guardar y Generar Factura</button>
                      @else
                        <button disabled type="submit" class="btn btn-primary btn-flat" id="generar_venta">Guardar y Generar Factura</button>
                      @endif
                      <button type="button" class="btn btn-danger pull-right btn-flat" data-dismiss="modal"><i class="fa fa-times"></i>Cancelar</button>
                  </div>
              </div>
           </div>
        </div>
     </div>
  </div>
</div>
