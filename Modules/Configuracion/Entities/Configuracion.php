<?php

namespace Modules\Configuracion\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{

    protected $table = 'configuracion__configuracions';
    public $translatedAttributes = [];
    protected $fillable = ['slug', 'descripcion', 'value', 'orden', 'admin'];
}
