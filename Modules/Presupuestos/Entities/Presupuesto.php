<?php

namespace Modules\Presupuestos\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{

    protected $table = 'presupuestos__presupuestos';
    public $translatedAttributes = [];
    protected $fillable = ['nro_presupuesto','nombre_cliente','precio_total','direccion_cliente','telefono_cliente','email_cliente'];

    public function detalles(){
        return $this->hasMany('Modules\Presupuestos\Entities\PresupuestoDetalle');
      }
}
