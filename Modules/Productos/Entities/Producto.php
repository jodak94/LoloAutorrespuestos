<?php

namespace Modules\Productos\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Log;
class Producto extends Model
{

    protected $table = 'productos__productos';
    public $translatedAttributes = [];
    protected $fillable = ['codigo','nombre','descripcion','stock','stock_critico','precio'];

    protected $appends = [
      'url_foto'
    ];

    public function getUrlFotoAttribute(){
      if(isset($this->attributes['foto']) && $this->attributes['foto'] != '')
        return url($this->attributes['foto']);
      else
        return url('images/default-product.jpg');
    }

    public function setPrecioAttribute($value){
      $this->attributes['precio'] =  str_replace(',', '.',str_replace('.', '', $value));
    }
}
