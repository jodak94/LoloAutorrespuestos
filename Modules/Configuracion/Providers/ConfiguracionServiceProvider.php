<?php

namespace Modules\Configuracion\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Configuracion\Events\Handlers\RegisterConfiguracionSidebar;

class ConfiguracionServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterConfiguracionSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('configuracions', array_dot(trans('configuracion::configuracions')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('configuracion', 'permissions');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Configuracion\Repositories\ConfiguracionRepository',
            function () {
                $repository = new \Modules\Configuracion\Repositories\Eloquent\EloquentConfiguracionRepository(new \Modules\Configuracion\Entities\Configuracion());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Configuracion\Repositories\Cache\CacheConfiguracionDecorator($repository);
            }
        );
// add bindings

    }
}
