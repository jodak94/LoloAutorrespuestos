<table cellspacing="0" width="80%" style="margin:auto;border-collapse: collapse">
  <tr style="border-left: 0;border-right: 0 ;border-bottom: 1px;border-top: 0; border-style: solid">
       <td width="30%">
           @if ($format == "html")
               <img src="{{ url('images/logo.png') }}" width="150px" style="vertical-align: unset">
           @else
               <img src="{{ public_path('images/logo.png') }}" width="150px; vertical-align: top">
           @endif
        </td>
        <td width="40%" style="">
           <div style="display:block;font-size:14px;margin:auto;margin-top:5px">
               <p style="margin: 0;">Lolo Autorepuestos SRL</p>
               <p style="margin: 0;">Avda. Defensores Chaco c/ Cerro Corá</p>
               <p style="margin: 0;">(021) 505 985</p>
           </div>
       </td>
       <td width="30%" style="text-align: right">
           <p style="margin-bottom: 0;"><b>Presupuesto</b></p>
           <p><b>N° {{ $datosPresupuesto["nro_presupuesto"] }}</b></p>
           <br>
       </td>
   </tr>
</table>
<table  cellspacing="0" width="80%" style="margin:auto;border-collapse: collapse" >
        <tr>
            <td>
                <p><b>Nombre del Cliente: </b> {{$datosPresupuesto["nombre_cliente"]}} </p>
                <p><b>Dirección: </b> {{$datosPresupuesto["direccion_cliente"]}}</p>
            </td>
            <td colspan="2">
                    <p><b>Telefono: </b> {{$datosPresupuesto["telefono_cliente"]}}</p>
                    <p><b>E-mail: </b> {{$datosPresupuesto["email_cliente"]}}</p>
            </td>
            <td colspan="2">
              <p style="text-align: right"><b>Fecha:</b> {{$datosPresupuesto["fecha"]}}</p>
              <p style="color: #fff">.</p>
            </td>
        </tr>
        <tr style="text-align: center;font-weight: bold">
            <td width="40%" style="border: 1px solid black">Concepto</td>
            <td width="15%" style="border: 1px solid black">Cantidad</td>
            <td width="15%" style="border: 1px solid black">Precio Unitario</td>
            <td width="15%" style="border: 1px solid black">Descuento</td>
            <td width="15%" style="border: 1px solid black">Precio Total</td>
        </tr>
        <tr style="border: 1px solid black">
            <td style="border: 1px solid black">
            @foreach ($presupuestoDetalles as  $detalle)
                <p style="margin:0 0 0 1%">{{ $detalle["producto"] }}</p>
            @endforeach
            </td>
            <td style="border: 1px solid black">
            @foreach ($presupuestoDetalles as  $detalle)
                <p style="margin:0;text-align:center">{{ $detalle["cantidad"] }}</p>
            @endforeach
            </td>
            <td style="border: 1px solid black">
            @foreach ($presupuestoDetalles as  $detalle)
                <p style="margin:0;text-align:right">{{ $detalle["precio_unitario"] }}</p>
            @endforeach
            </td>
            <td style="border: 1px solid black">
            @foreach ($presupuestoDetalles as  $detalle)
                <p style="margin:0;text-align:right">{{ $detalle["descuento"] }}</p>
            @endforeach
            </td>
            <td style="border: 1px solid black">
            @foreach ($presupuestoDetalles as  $detalle)
                <p style="margin:0;text-align:right">{{ $detalle["subtotal"] }}</p>
            @endforeach
            </td>
        </tr>

        <tr>
                <td style="border: 0" colspan="3"></td>
                <td style="border: 1px solid black;font-weight: bold">Total: </td>
                <td style="border: 1px solid black;text-align:right"> {{$datosPresupuesto["total"]}} </td>
            </tr>
            <tr style="border: 0">
                    <td colspan="3">
                        <p>El presupuesto tiene una validez de {{\Configuracion::where('slug', 'periodo_validez_presupuesto')->first()->value}} días.</p>
                        <p>Formas y términos de pago a definir.</p>
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr style="border: 0">
                        <td colspan="2"></td>
                        <td colspan="3">
                            Aceptación por parte del cliente
                            <div style="border: 1px solid black;height: 150px;">
                                <p>Firma y fecha:</p>
                            </div>
                        </td>
                    </tr>
</table>
