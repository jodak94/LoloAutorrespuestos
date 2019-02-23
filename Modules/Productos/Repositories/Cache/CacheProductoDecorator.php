<?php

namespace Modules\Productos\Repositories\Cache;

use Modules\Productos\Repositories\ProductoRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheProductoDecorator extends BaseCacheDecorator implements ProductoRepository
{
    public function __construct(ProductoRepository $producto)
    {
        parent::__construct();
        $this->entityName = 'productos.productos';
        $this->repository = $producto;
    }
}
