<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Factura</title>
</head>
<style>
@page { margin: 0px; }
body { margin: 0px; }
</style>
<body style="font-size:10px;" >
    @if(isset($background))
        <img src="{{ public_path('images/factura_Lolo.png')}}" style="width:100%;margin-top:10px;"/>
    @endif
    @include('ventas::pdf.partials.factura-partial')
</body>

</html>
