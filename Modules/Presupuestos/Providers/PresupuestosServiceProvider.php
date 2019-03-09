<?php

namespace Modules\Presupuestos\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Presupuestos\Events\Handlers\RegisterPresupuestosSidebar;

class PresupuestosServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterPresupuestosSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('presupuestos', array_dot(trans('presupuestos::presupuestos')));
            $event->load('presupuestodetalles', array_dot(trans('presupuestos::presupuestodetalles')));
            // append translations


        });
    }

    public function boot()
    {
        $this->publishConfig('presupuestos', 'permissions');

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
            'Modules\Presupuestos\Repositories\PresupuestoRepository',
            function () {
                $repository = new \Modules\Presupuestos\Repositories\Eloquent\EloquentPresupuestoRepository(new \Modules\Presupuestos\Entities\Presupuesto());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Presupuestos\Repositories\Cache\CachePresupuestoDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Presupuestos\Repositories\PresupuestoDetalleRepository',
            function () {
                $repository = new \Modules\Presupuestos\Repositories\Eloquent\EloquentPresupuestoDetalleRepository(new \Modules\Presupuestos\Entities\PresupuestoDetalle());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Presupuestos\Repositories\Cache\CachePresupuestoDetalleDecorator($repository);
            }
        );
// add bindings


    }
}
