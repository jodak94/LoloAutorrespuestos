<?php

namespace Modules\Productos\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Productos\Events\Handlers\RegisterProductosSidebar;

class ProductosServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterProductosSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('productos', array_dot(trans('productos::productos')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('productos', 'permissions');

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
            'Modules\Productos\Repositories\ProductoRepository',
            function () {
                $repository = new \Modules\Productos\Repositories\Eloquent\EloquentProductoRepository(new \Modules\Productos\Entities\Producto());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Productos\Repositories\Cache\CacheProductoDecorator($repository);
            }
        );
// add bindings

    }
}
