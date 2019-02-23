<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/clientes'], function (Router $router) {
    $router->bind('cliente', function ($id) {
        return app('Modules\Clientes\Repositories\ClienteRepository')->find($id);
    });
    $router->get('clientes', [
        'as' => 'admin.clientes.cliente.index',
        'uses' => 'ClienteController@index',
        'middleware' => 'can:clientes.clientes.index'
    ]);
    $router->get('clientes/create', [
        'as' => 'admin.clientes.cliente.create',
        'uses' => 'ClienteController@create',
        'middleware' => 'can:clientes.clientes.create'
    ]);
    $router->post('clientes', [
        'as' => 'admin.clientes.cliente.store',
        'uses' => 'ClienteController@store',
        'middleware' => 'can:clientes.clientes.create'
    ]);
    $router->get('clientes/{cliente}/edit', [
        'as' => 'admin.clientes.cliente.edit',
        'uses' => 'ClienteController@edit',
        'middleware' => 'can:clientes.clientes.edit'
    ]);
    $router->put('clientes/{cliente}', [
        'as' => 'admin.clientes.cliente.update',
        'uses' => 'ClienteController@update',
        'middleware' => 'can:clientes.clientes.edit'
    ]);
    $router->delete('clientes/{cliente}', [
        'as' => 'admin.clientes.cliente.destroy',
        'uses' => 'ClienteController@destroy',
        'middleware' => 'can:clientes.clientes.destroy'
    ]);
    $router->bind('datosfacturacion', function ($id) {
        return app('Modules\Clientes\Repositories\DatosFacturacionRepository')->find($id);
    });
    $router->get('datosfacturacions', [
        'as' => 'admin.clientes.datosfacturacion.index',
        'uses' => 'DatosFacturacionController@index',
        'middleware' => 'can:clientes.datosfacturacions.index'
    ]);
    $router->get('datosfacturacions/create', [
        'as' => 'admin.clientes.datosfacturacion.create',
        'uses' => 'DatosFacturacionController@create',
        'middleware' => 'can:clientes.datosfacturacions.create'
    ]);
    $router->post('datosfacturacions', [
        'as' => 'admin.clientes.datosfacturacion.store',
        'uses' => 'DatosFacturacionController@store',
        'middleware' => 'can:clientes.datosfacturacions.create'
    ]);
    $router->post('datosfacturacions/store-ajax', [
        'as' => 'admin.clientes.datosfacturacion.store_ajax',
        'uses' => 'DatosFacturacionController@store_ajax',
        'middle,ware' => 'can:clientes.datosfacturacions.create'
    ]);
    $router->get('datosfacturacions/{datosfacturacion}/edit', [
        'as' => 'admin.clientes.datosfacturacion.edit',
        'uses' => 'DatosFacturacionController@edit',
        'middleware' => 'can:clientes.datosfacturacions.edit'
    ]);
    $router->get('datosfacturacions/search-ajax', [
      'as' => 'admin.clientes.datosfacturacion.search_ajax',
      'uses' => 'DatosFacturacionController@search_ajax',
    ]);
    $router->put('datosfacturacions/{datosfacturacion}', [
        'as' => 'admin.clientes.datosfacturacion.update',
        'uses' => 'DatosFacturacionController@update',
        'middleware' => 'can:clientes.datosfacturacions.edit'
    ]);
    $router->post('datosfacturacions/update-ajax', [
        'as' => 'admin.clientes.datosfacturacion.update_ajax',
        'uses' => 'DatosFacturacionController@update_ajax',
        'middleware' => 'can:clientes.datosfacturacions.edit'
    ]);
    $router->delete('datosfacturacions/{datosfacturacion}', [
        'as' => 'admin.clientes.datosfacturacion.destroy',
        'uses' => 'DatosFacturacionController@destroy',
        'middleware' => 'can:clientes.datosfacturacions.destroy'
    ]);
// append


});
