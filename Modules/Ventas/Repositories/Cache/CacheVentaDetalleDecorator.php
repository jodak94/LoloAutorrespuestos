<?php

namespace Modules\Ventas\Repositories\Cache;

use Modules\Ventas\Repositories\VentaDetalleRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheVentaDetalleDecorator extends BaseCacheDecorator implements VentaDetalleRepository
{
    public function __construct(VentaDetalleRepository $ventadetalle)
    {
        parent::__construct();
        $this->entityName = 'ventas.ventadetalles';
        $this->repository = $ventadetalle;
    }
}
