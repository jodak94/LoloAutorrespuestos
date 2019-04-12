<table  cellspacing="0" width="80%" style="margin:auto;border-collapse: collapse" >
       <tr style="border-left: 0;border-right: 0 ;border-bottom: 1px;border-top: 0; border-style: solid">
            <td colspan="3" >
                @if ($format == "html")
                    <img src="{{ url('images/logo.png') }}" width="150px">
                @else
                    <img src="{{ public_path('images/logo.png') }}" width="150px">
                @endif

                <div style="padding-left:5%;display:inline-block;font-size:12px">
                    <p style="margin-top:2%">Lolo AutoRepuestos S.R.L</p>
                    <p>Avda. Defensores Chaco c/ Cerro Corá</p>
                    <p>(021) 505 985</p>
                </div>
            </td>
            <td colspan="2" style="text-align: center">
                <p><b>Presupuesto</b></p>
                <p><b>N° {{ $datosPresupuesto["nro_presupuesto"] }}</b></p>
                <br>
                <p style="text-align: left"><b>Fecha:</b> {{$datosPresupuesto["fecha"]}}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><b>Nombre del Cliente: </b> {{$datosPresupuesto["nombre_cliente"]}} </p>
                <p><b>Dirección: </b> {{$datosPresupuesto["direccion_cliente"]}}</p>
            </td>
            <td colspan="3">
                    <p><b>Telefono: </b> {{$datosPresupuesto["telefono_cliente"]}}</p>
                    <p><b>E-mail: </b> {{$datosPresupuesto["email_cliente"]}}</p>
            </td>
        </tr>
        <tr style="text-align: center;font-weight: bold">
            <td style="border: 1px solid black">Concepto</td>
            <td style="width: 4 %;border: 1px solid black">Cantidad</td>
            <td style="width: 16%;border: 1px solid black">Precio Unitario</td>
            <td style="width: 16%;border: 1px solid black">Descuento</td>
            <td style="width: 16%;border: 1px solid black">Precio Total</td>
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
