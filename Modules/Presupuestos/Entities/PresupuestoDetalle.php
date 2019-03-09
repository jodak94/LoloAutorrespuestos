<?php

namespace Modules\Presupuestos\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{

    protected $table = 'presupuestos__presupuestodetalles';
    public $translatedAttributes = [];
    protected $fillable = [];

    public function presupuesto(){
        return $this->belongsTo('Modules\Presupuestos\Entities\Presupuesto');
      }
}
