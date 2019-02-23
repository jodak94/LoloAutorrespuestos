<?php

namespace Modules\Ventas\Repositories\Cache;

use Modules\Ventas\Repositories\VentaRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheVentaDecorator extends BaseCacheDecorator implements VentaRepository
{
    public function __construct(VentaRepository $venta)
    {
        parent::__construct();
        $this->entityName = 'ventas.ventas';
        $this->repository = $venta;
    }
}
