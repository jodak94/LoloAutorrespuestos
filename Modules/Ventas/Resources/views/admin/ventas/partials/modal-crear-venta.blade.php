<div class="modal fade" id="facturaModal" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
        <div class="modal-header" style="background-color: #3c8dbc; color: #ffffff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h4 class="modal-title"><strong>Crear Venta</strong></h4></center>
        </div><br>
        <div class="modal-body">
           <div class="row" style="">
              <div class="col-sm-6">
                 {!! Form::normalInput('nro_factura', 'Nro Factura', $errors, (object)['nro_factura' => $nro_factura ], ['class' => 'form-control','readonly'=>'']) !!}
              </div>
              <div class="col-sm-6">
                 {!! Form:: normalSelect('tipo_factura', 'Tipo de Factura:', $errors, $tipos_factura) !!}
               </div>
           </div>
           {!! Form::normalInput('modal-monto-total', 'Total a Pagar:', $errors ,(object)['total_a_pagar_factura' => 0], ['readonly'=>'', 'name' => 'monto_pagado'] ) !!}
           {!! Form::normalInput('pago_cliente', 'Pago del Cliente:', $errors ,(object)['pago_cliente' => ''], ['required'=>'','autofocus'] ) !!}
           {!! Form::normalInput('vuelto_cliente', 'Vuelto:', $errors ,null, ['id'=>'vuelto','readonly'=>''] ) !!}
           <div id="plazo_credito-1" style="display:none">
              {!! Form:: normalSelect('plazo_credito', 'Plazo del Crédito:', $errors, [''=>'---','5'=>'5 días','10'=>'10 días','30'=>'30 días','60'=>'60 días','90'=>'90 días'],null,['id'=>'plazo_credito']) !!}
           </div>
           {!! Form::hidden('generar_factura', '0') !!}
           <input type="hidden" id="client_ip" name="client_ip">
           <div class="modal-body">
              <div class="box-footer">
                  <div class="modal-footer">
                      <button type="submit" disabled class="btn btn-primary btn-flat" id="generar_factura">Guardar y Generar Factura</button>
                      <button type="button" class="btn btn-danger pull-right btn-flat" data-dismiss="modal"><i class="fa fa-times"></i>Cancelar</button>
                  </div>
              </div>
           </div>
        </div>
     </div>
  </div>
</div>
