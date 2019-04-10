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
    $router->get('productos/search-ajax', [
        'as' => 'admin.productos.producto.search_ajax',
        'uses' => 'ProductoController@search_ajax',
    ]);

    $router->post('productos/update-stock', [
        'as' => 'admin.productos.producto.update_stock',
        'uses' => 'ProductoController@update_stock',
    ]);
    $router->get('productos/entrada', [
        'as' => 'admin.productos.producto.entrada',
        'uses' => 'ProductoController@entrada',
    ]);
    $router->get('productos/import', [
        'as' => 'admin.productos.producto.import',
        'uses' => 'ProductoController@import_view',
    ]);
    $router->post('productos/import_excel', [
        'as' => 'admin.productos.producto.import_productos',
        'uses' => 'ProductoController@import_productos',
    ]);
    $router->post('productos/validation', [
        'as' => 'admin.productos.producto.validation',
        'uses' => 'ProductoController@producto_validation',
    ]);
    $router->post('productos/store_ajax', [
        'as' => 'admin.productos.producto.store_ajax',
        'uses' => 'ProductoController@store_ajax',
    ]);
// append

});
