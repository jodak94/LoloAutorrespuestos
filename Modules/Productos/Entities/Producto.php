<?php

namespace Modules\Productos\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{

    protected $table = 'productos__productos';
    public $translatedAttributes = [];
    protected $fillable = ['codigo','nombre','descripcion','stock','stock_critico','costo','precio'];
}
