<?php

namespace Modules\Ventas\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use Translatable;

    protected $table = 'ventas__ventadetalles';
    public $translatedAttributes = [];
    protected $fillable = [];
}
