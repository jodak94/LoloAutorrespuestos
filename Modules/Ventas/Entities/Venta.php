<?php

namespace Modules\Ventas\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Log;

class Venta extends Model
{

    protected $table = 'ventas__ventas';
    public $translatedAttributes = [];
    protected $fillable = [
      'nro_factura', 'monto_total', 'datos_id', 'tipo_factura', 'monto_pagado',
      'total_iva', 'precio_total_letras', 'plazo_credito', 'razon_social',
      'ruc', 'direccion', 'telefono', 'generar_factura', 'parcial'
    ];
    protected $appends = [
      'monto_total_format', 'monto_pagado_format', 'total_iva_format', 'tipo_factura_format'
    ];

    public static $tipos_factura = [
      'contado' => 'Contado',
      'credito' => 'Crédito'
    ];

    public function detalles(){
      return $this->hasMany('Modules\Ventas\Entities\VentaDetalle');
    }

    public function getMontoPagadoFormatAttribute(){
      return number_format($this->attributes['monto_pagado'], 0, ',', '.');
    }

    public function getMontoTotalFormatAttribute(){
      return number_format($this->attributes['monto_total'], 0, ',', '.');
    }

    public function getTotalIvaFormatAttribute(){
      return number_format($this->attributes['total_iva'], 0, ',', '.');
    }

    public function getTipoFacturaFormatAttribute(){
      if(isset($this->tipo_factura))
        return self::$tipos_factura[$this->tipo_factura];
      else
        return '';
    }
}
