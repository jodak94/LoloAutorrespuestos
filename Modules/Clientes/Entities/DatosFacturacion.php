<?php

namespace Modules\Clientes\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class DatosFacturacion extends Model
{

    protected $table = 'clientes__datosfacturacions';
    public $translatedAttributes = [];
    protected $fillable = ['razon_social', 'ruc', 'telefono', 'direccion'];
}
