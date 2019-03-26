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
      'ruc', 'direccion', 'telefono'
    ];
    public static $descuentos = [
      '1'    => '--',
      '0.95' => '5%',
      '0.90' => '10%',
      '0.85' => '15%',
      '0.80' => '20%',
    ];

    public static $tipos_factura = [
      'contado' => 'Contado',
      'credito' => 'Crédito'
    ];

    public function detalles(){
      return $this->hasMany('Modules\Ventas\Entities\VentaDetalle');
    }

    public function getMontoPagadoFormaAttribute(){
      return number_format($this->attributes['monto_pagado'], 0, ',', '.');
    }

    public function getMontoTotalAttribute(){
      return number_format($this->attributes['monto_total'], 0, ',', '.');
    }

    public function getTotalIvaAttribute(){
      return number_format($this->attributes['total_iva'], 0, ',', '.');
    }

    public function setMontoPagadoAttribute($value){
      $this->attributes['monto_pagado'] =  str_replace(',', '.',str_replace('.', '', $value));
    }

    public function setMontoTotalAttribute($value){
      $this->attributes['monto_total'] =  str_replace(',', '.',str_replace('.', '', $value));
    }

    public function setTotalIvaAttribute($value){
      $this->attributes['total_iva'] =  str_replace(',', '.',str_replace('.', '', $value));
    }
}
