<?php

namespace Modules\Configuracion\Repositories\Cache;

use Modules\Configuracion\Repositories\ConfiguracionRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheConfiguracionDecorator extends BaseCacheDecorator implements ConfiguracionRepository
{
    public function __construct(ConfiguracionRepository $configuracion)
    {
        parent::__construct();
        $this->entityName = 'configuracion.configuracions';
        $this->repository = $configuracion;
    }
}
