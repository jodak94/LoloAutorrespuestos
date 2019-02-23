<?php

namespace Modules\Clientes\Repositories\Cache;

use Modules\Clientes\Repositories\DatosFacturacionRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheDatosFacturacionDecorator extends BaseCacheDecorator implements DatosFacturacionRepository
{
    public function __construct(DatosFacturacionRepository $datosfacturacion)
    {
        parent::__construct();
        $this->entityName = 'clientes.datosfacturacions';
        $this->repository = $datosfacturacion;
    }
}
