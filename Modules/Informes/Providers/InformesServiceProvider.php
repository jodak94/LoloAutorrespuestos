<?php

namespace Modules\Informes\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Informes\Events\Handlers\RegisterInformesSidebar;

class InformesServiceProvider extends ServiceProvider
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
        $this->app['events']->listen(BuildingSidebar::class, RegisterInformesSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('informes', array_dot(trans('informes::informes')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('informes', 'permissions');

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
            'Modules\Informes\Repositories\InformeRepository',
            function () {
                $repository = new \Modules\Informes\Repositories\Eloquent\EloquentInformeRepository(new \Modules\Informes\Entities\Informe());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Informes\Repositories\Cache\CacheInformeDecorator($repository);
            }
        );
// add bindings

    }
}
