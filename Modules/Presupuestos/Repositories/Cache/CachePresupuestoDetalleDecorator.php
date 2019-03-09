<?php

namespace Modules\Presupuestos\Repositories\Cache;

use Modules\Presupuestos\Repositories\PresupuestoDetalleRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePresupuestoDetalleDecorator extends BaseCacheDecorator implements PresupuestoDetalleRepository
{
    public function __construct(PresupuestoDetalleRepository $presupuestodetalle)
    {
        parent::__construct();
        $this->entityName = 'presupuestos.presupuestodetalles';
        $this->repository = $presupuestodetalle;
    }
}
