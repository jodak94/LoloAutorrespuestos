<?php

namespace Modules\Ventas\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{

    protected $table = 'ventas__ventadetalles';
    public $translatedAttributes = [];
    protected $fillable = [];

    public function venta(){
      return $this->belongsTo('Modules\Ventas\Entities\Venta');
    }

    public function producto(){
      return $this->belongsTo('Modules\Productos\Entities\Producto');
    }

    public function getPrecioUnitarioAttribute(){
      return number_format($this->attributes['precio_unitario'], 0, ',', '.');
    }

    public function getPrecioSubtotalAttribute(){
      return number_format($this->attributes['precio_subtotal'], 0, ',', '.');
    }
}
