<?php

namespace Modules\Configuracion\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use DB;
use Modules\Configuracion\Entities\Configuracion;
class ConfiguracionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        for ($i=1; $i <= 3; $i++) {
          $conf = new Configuracion;
          $conf->slug = 'factura';
          $conf->descripcion = 'Configuración Factura ' . $i;
          $conf->admin = false;
          $conf->orden = $i;
          $conf->value = '001';
          if($i == 3)
            $conf->value = '0000001';
          $conf->save();
        }

        $conf = new Configuracion;
        $conf->slug = 'periodo_validez_presupuesto';
        $conf->descripcion = 'Periodo de validez de presupuestos en días ';
        $conf->admin = false;
        $conf->orden = 4;
        $conf->value = 30;
        $conf->save();

        $conf = new Configuracion;
        $conf->slug = 'descuentos';
        $conf->descripcion = 'Descuentos';
        $conf->admin = false;
        $conf->orden = 5;
        $conf->value = '{"1":"--", "0.95":"5%", "0.9":"10%", "0.85":"15%", "0.8":"20%"}';
        $conf->save();
    }
}
