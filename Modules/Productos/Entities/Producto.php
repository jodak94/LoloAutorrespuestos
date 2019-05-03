<?php

namespace Modules\Productos\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Log;
class Producto extends Model
{

    protected $table = 'productos__productos';
    public $translatedAttributes = [];
    protected $fillable = ['codigo','nombre','stock','stock_critico','precio', 'costo', 'descuento','descripcion'];

    protected $appends = [
      'url_foto',
      'precio_format',
      'costo_format'
    ];

    public function getUrlFotoAttribute(){
      if(isset($this->attributes['foto']) && $this->attributes['foto'] != '')
        return url($this->attributes['foto']);
      else
        return url('images/default-product.jpg');
    }

    public function setCostoAttribute($value){
      $this->attributes['costo'] =  str_replace(',', '.',str_replace('.', '', $value));
    }

    public function setPrecioAttribute($value){
      $this->attributes['precio'] =  str_replace(',', '.',str_replace('.', '', $value));
    }

    public function getPrecioFormatAttribute(){
      return number_format($this->attributes['precio'], 0, ',', '.');
    }

    public function getCostoFormatAttribute(){
      return number_format($this->attributes['costo'], 0, ',', '.');
    }
}
