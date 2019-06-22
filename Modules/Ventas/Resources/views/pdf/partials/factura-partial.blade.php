<div id="fecha" style="position: absolute;left: {{ $facturaBoxes->fecha->x }}cm;top: {{ $facturaBoxes->fecha->y }}cm">{{date("d-m-Y",strtotime(Carbon\Carbon::now()))}}</div>

@php
if($venta->tipo_factura == 'credito') {
    echo '<div id="credito" style="position: absolute;left: '.$facturaBoxes->credito->x.'cm;top: '.$facturaBoxes->credito->y.'cm">X</div>';
}else {
    echo '<div id="credito" style="position: absolute;left: '.$facturaBoxes->contado->x.'cm;top: '.$facturaBoxes->contado->y.'cm">X</div>';
}
@endphp

<div id="nombre" style="position: absolute;left: {{ $facturaBoxes->nombre->x }}cm;top: {{ $facturaBoxes->nombre->y }}cm"> {{$venta->razon_social}}</div>
<div id="ruc" style="position: absolute;left: {{ $facturaBoxes->ruc->x }}cm;top: {{ $facturaBoxes->ruc->y }}cm">{{$venta->ruc}}</div>
<div id="direccion" style="position: absolute;left: {{ $facturaBoxes->direccion->x }}cm;top: {{ $facturaBoxes->direccion->y }}cm"> {{$venta->direccion}}</div>
<div id="telefono" style="position: absolute;left: {{ $facturaBoxes->telefono->x }}cm;top: {{ $facturaBoxes->telefono->y }}cm"> {{$venta->telefono}}</div>
<div id="vencimiento" style="position: absolute;left: {{ $facturaBoxes->vencimiento->x }}cm;top: {{ $facturaBoxes->vencimiento->y }}cm"></div>

@php
$y = $facturaBoxes->producto->y;

foreach($venta->detalles as $detalle) {
    echo '<div id="cantidad" style="position: absolute;left:'.$facturaBoxes->cantidad->x.'cm;top: '.$y.'cm">'.$detalle->cantidad.'</div>';
    echo '<div id="producto" style="position: absolute;left: '.$facturaBoxes->producto->x.'cm;top: '.$y.'cm">'.$detalle->producto->nombre.'</div>';
    echo '<div id="precio-unitario" style="position: absolute;left: '.$facturaBoxes->precio_unitario->x.'cm;top: '.$y.'cm">'.number_format($detalle->descuento * (int)str_replace(".","",$detalle->precio_unitario)).'</div>';
    echo '<div id="iva" style="position: absolute;left: '.$facturaBoxes->iva->x.'cm;top: '.$y.'cm">'.number_format($detalle->cantidad * $detalle->descuento * (int)str_replace(".","",$detalle->precio_unitario),0,',','.').'</div>';
    $y += 0.35;
}

@endphp
<div id="subtotal" style="position: absolute;left: {{ $facturaBoxes->subtotal->x }}cm;top: {{ $facturaBoxes->subtotal->y }}cm">{{number_format($venta->monto_total,0,',','.')}}</div>
<div id="total-letras" style="position: absolute;left: {{ $facturaBoxes->total_letras->x }}cm;top: {{ $facturaBoxes->total_letras->y }}cm">{{$venta->precio_total_letras}}</div>
<div id="total" style="position: absolute;left: {{ $facturaBoxes->total->x }}cm;top: {{ $facturaBoxes->total->y }}cm">{{number_format($venta->monto_total,0,',','.')}}</div>
<div id="iva10" style="position: absolute;left: {{ $facturaBoxes->iva_10->x }}cm;top: {{ $facturaBoxes->iva_10->y }}cm">{{number_format($venta->total_iva,0,',','.')}}</div>
<div id="total-iva10" style="position: absolute;left: {{ $facturaBoxes->total_iva->x }}cm;top: {{ $facturaBoxes->total_iva->y }}cm">{{number_format($venta->total_iva,0,',','.')}}</div>

<!-- duplicado  -->

<div id="fecha" style="position: absolute;left: {{ $facturaBoxes->fecha->x }}cm;top: {{ $facturaBoxes->fecha->y + $facturaBoxes->duplicado }}cm">{{date("d-m-Y",strtotime(Carbon\Carbon::now()))}}</div>

@php
if($venta->tipo_factura == 'credito') {
    echo '<div id="credito" style="position: absolute;left: '.$facturaBoxes->credito->x.'cm;top: '.($facturaBoxes->credito->y + $facturaBoxes->duplicado).'cm">X</div>';
}else {
    echo '<div id="credito" style="position: absolute;left: '.$facturaBoxes->contado->x.'cm;top: '.($facturaBoxes->contado->y + $facturaBoxes->duplicado).'cm">X</div>';
}
@endphp

<div id="nombre" style="position: absolute;left: {{ $facturaBoxes->nombre->x }}cm;top: {{ $facturaBoxes->nombre->y + $facturaBoxes->duplicado }}cm"> {{$venta->razon_social}}</div>
<div id="ruc" style="position: absolute;left: {{ $facturaBoxes->ruc->x }}cm;top: {{ $facturaBoxes->ruc->y + $facturaBoxes->duplicado }}cm">{{$venta->ruc}}</div>
<div id="direccion" style="position: absolute;left: {{ $facturaBoxes->direccion->x }}cm;top: {{ $facturaBoxes->direccion->y + $facturaBoxes->duplicado }}cm"> {{$venta->direccion}}</div>
<div id="telefono" style="position: absolute;left: {{ $facturaBoxes->telefono->x }}cm;top: {{ $facturaBoxes->telefono->y + $facturaBoxes->duplicado }}cm"> {{$venta->telefono}}</div>
<div id="vencimiento" style="position: absolute;left: {{ $facturaBoxes->vencimiento->x }}cm;top: {{ $facturaBoxes->vencimiento->y + $facturaBoxes->duplicado }}cm"></div>

@php
$y = $facturaBoxes->producto->y + $facturaBoxes->duplicado;
foreach($venta->detalles as $detalle) {
    echo '<div id="cantidad" style="position: absolute;left:'.$facturaBoxes->cantidad->x.'cm;top: '.$y.'cm">'.$detalle->cantidad.'</div>';
    echo '<div id="producto" style="position: absolute;left: '.$facturaBoxes->producto->x.'cm;top: '.$y.'cm">'.$detalle->producto->nombre.'</div>';
    echo '<div id="precio-unitario" style="position: absolute;left: '.$facturaBoxes->precio_unitario->x.'cm;top: '.$y.'cm">'.number_format($detalle->descuento * (int)str_replace(".","",$detalle->precio_unitario)).'</div>';
    echo '<div id="iva" style="position: absolute;left: '.$facturaBoxes->iva->x.'cm;top: '.$y.'cm">'.number_format($detalle->cantidad * $detalle->descuento * (int)str_replace(".","",$detalle->precio_unitario),0,',','.').'</div>';
    $y += 0.35;
}

@endphp
<div id="subtotal" style="position: absolute;left: {{ $facturaBoxes->subtotal->x }}cm;top: {{ $facturaBoxes->subtotal->y + $facturaBoxes->duplicado }}cm">{{number_format($venta->monto_total,0,',','.')}}</div>
<div id="total-letras" style="position: absolute;left: {{ $facturaBoxes->total_letras->x }}cm;top: {{ $facturaBoxes->total_letras->y + $facturaBoxes->duplicado }}cm">{{$venta->precio_total_letras}}</div>
<div id="total" style="position: absolute;left: {{ $facturaBoxes->total->x }}cm;top: {{ $facturaBoxes->total->y + $facturaBoxes->duplicado }}cm">{{number_format($venta->monto_total,0,',','.')}}</div>
<div id="iva10" style="position: absolute;left: {{ $facturaBoxes->iva_10->x }}cm;top: {{ $facturaBoxes->iva_10->y + $facturaBoxes->duplicado }}cm">{{number_format($venta->total_iva,0,',','.')}}</div>
<div id="total-iva10" style="position: absolute;left: {{ $facturaBoxes->total_iva->x }}cm;top: {{ $facturaBoxes->total_iva->y + $facturaBoxes->duplicado }}cm">{{number_format($venta->total_iva,0,',','.')}}</div>
