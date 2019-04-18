<?php

namespace Modules\Informes\Repositories\Cache;

use Modules\Informes\Repositories\InformeRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheInformeDecorator extends BaseCacheDecorator implements InformeRepository
{
    public function __construct(InformeRepository $informe)
    {
        parent::__construct();
        $this->entityName = 'informes.informes';
        $this->repository = $informe;
    }
}
