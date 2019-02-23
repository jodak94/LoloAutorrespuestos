<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/productos'], function (Router $router) {
    $router->bind('producto', function ($id) {
        return app('Modules\Productos\Repositories\ProductoRepository')->find($id);
    });
    $router->get('productos', [
        'as' => 'admin.productos.producto.index',
        'uses' => 'ProductoController@index',
        'middleware' => 'can:productos.productos.index'
    ]);
    $router->get('productos/create', [
        'as' => 'admin.productos.producto.create',
        'uses' => 'ProductoController@create',
        'middleware' => 'can:productos.productos.create'
    ]);
    $router->post('productos', [
        'as' => 'admin.productos.producto.store',
        'uses' => 'ProductoController@store',
        'middleware' => 'can:productos.productos.create'
    ]);
    $router->get('productos/{producto}/edit', [
        'as' => 'admin.productos.producto.edit',
        'uses' => 'ProductoController@edit',
        'middleware' => 'can:productos.productos.edit'
    ]);
    $router->put('productos/{producto}', [
        'as' => 'admin.productos.producto.update',
        'uses' => 'ProductoController@update',
        'middleware' => 'can:productos.productos.edit'
    ]);
    $router->delete('productos/{producto}', [
        'as' => 'admin.productos.producto.destroy',
        'uses' => 'ProductoController@destroy',
        'middleware' => 'can:productos.productos.destroy'
    ]);
// append

});
