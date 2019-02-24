<?php

namespace Modules\Ventas\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use Translatable;

    protected $table = 'ventas__ventas';
    public $translatedAttributes = [];
    protected $fillable = [];

    public static $tipos_factura = [
      'contado' => 'Contado',
      'credito' => 'Crédito'
    ];
}
