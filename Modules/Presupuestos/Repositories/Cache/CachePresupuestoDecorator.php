<?php

namespace Modules\Presupuestos\Repositories\Cache;

use Modules\Presupuestos\Repositories\PresupuestoRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePresupuestoDecorator extends BaseCacheDecorator implements PresupuestoRepository
{
    public function __construct(PresupuestoRepository $presupuesto)
    {
        parent::__construct();
        $this->entityName = 'presupuestos.presupuestos';
        $this->repository = $presupuesto;
    }
}
