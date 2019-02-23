<?php

namespace Modules\Clientes\Repositories\Cache;

use Modules\Clientes\Repositories\ClienteRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheClienteDecorator extends BaseCacheDecorator implements ClienteRepository
{
    public function __construct(ClienteRepository $cliente)
    {
        parent::__construct();
        $this->entityName = 'clientes.clientes';
        $this->repository = $cliente;
    }
}
