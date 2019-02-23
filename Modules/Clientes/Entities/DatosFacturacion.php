<?php

namespace Modules\Clientes\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class DatosFacturacion extends Model
{

    protected $table = 'clientes__datosfacturacions';
    public $translatedAttributes = [];
    protected $fillable = ['razon_social', 'ruc', 'telefono', 'direccion'];

    public function cliente(){
      return $this->hasOne('Modules\Clientes\Entities\Cliente', 'datos_factura_id');
    }
}
