<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/configuracion'], function (Router $router) {
    $router->bind('configuracion', function ($id) {
        return app('Modules\Configuracion\Repositories\ConfiguracionRepository')->find($id);
    });
    $router->get('configuracions', [
        'as' => 'admin.configuracion.configuracion.index',
        'uses' => 'ConfiguracionController@index',
        'middleware' => 'can:configuracion.configuracions.index'
    ]);
    $router->get('configuracions/create', [
        'as' => 'admin.configuracion.configuracion.create',
        'uses' => 'ConfiguracionController@create',
        'middleware' => 'can:configuracion.configuracions.create'
    ]);
    $router->get('configuracions/configurar', [
        'as' => 'admin.configuracion.configuracion.configurar',
        'uses' => 'ConfiguracionController@configurar',
    ]);
    $router->post('configuracions', [
        'as' => 'admin.configuracion.configuracion.store',
        'uses' => 'ConfiguracionController@store',
        'middleware' => 'can:configuracion.configuracions.create'
    ]);
    $router->get('configuracions/{configuracion}/edit', [
        'as' => 'admin.configuracion.configuracion.edit',
        'uses' => 'ConfiguracionController@edit',
        'middleware' => 'can:configuracion.configuracions.edit'
    ]);
    $router->put('configuracions/{configuracion}', [
        'as' => 'admin.configuracion.configuracion.update',
        'uses' => 'ConfiguracionController@update',
        'middleware' => 'can:configuracion.configuracions.edit'
    ]);
    $router->post('configuracions/configurar-store', [
        'as' => 'admin.configuracion.configuracion.updateConfigurar',
        'uses' => 'ConfiguracionController@updateConfigurar',
    ]);
    $router->delete('configuracions/{configuracion}', [
        'as' => 'admin.configuracion.configuracion.destroy',
        'uses' => 'ConfiguracionController@destroy',
        'middleware' => 'can:configuracion.configuracions.destroy'
    ]);
// append

});
