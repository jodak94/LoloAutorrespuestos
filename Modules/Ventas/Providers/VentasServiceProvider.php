<?php

namespace Modules\Ventas\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Ventas\Events\Handlers\RegisterVentasSidebar;

class VentasServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterVentasSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('ventas', array_dot(trans('ventas::ventas')));
            $event->load('ventadetalles', array_dot(trans('ventas::ventadetalles')));
            // append translations


        });
    }

    public function boot()
    {
        $this->publishConfig('ventas', 'permissions');

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
            'Modules\Ventas\Repositories\VentaRepository',
            function () {
                $repository = new \Modules\Ventas\Repositories\Eloquent\EloquentVentaRepository(new \Modules\Ventas\Entities\Venta());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Ventas\Repositories\Cache\CacheVentaDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Ventas\Repositories\VentaDetalleRepository',
            function () {
                $repository = new \Modules\Ventas\Repositories\Eloquent\EloquentVentaDetalleRepository(new \Modules\Ventas\Entities\VentaDetalle());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Ventas\Repositories\Cache\CacheVentaDetalleDecorator($repository);
            }
        );
// add bindings


    }
}
