<?php

namespace Modules\Clientes\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $table = 'clientes__clientes';
    public $translatedAttributes = [];
    protected $fillable = ['nombre', 'apellido', 'datos_factura_id'];
}
