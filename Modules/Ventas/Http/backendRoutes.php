<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/ventas'], function (Router $router) {
    $router->bind('venta', function ($id) {
        return app('Modules\Ventas\Repositories\VentaRepository')->find($id);
    });
    $router->get('ventas', [
        'as' => 'admin.ventas.venta.index',
        'uses' => 'VentaController@index',
        'middleware' => 'can:ventas.ventas.index'
    ]);
    $router->get('ventas/index-ajax', [
        'as' => 'admin.ventas.venta.index_ajax',
        'uses' => 'VentaController@index_ajax',
        'middleware' => 'can:ventas.ventas.index'
    ]);
    $router->get('ventas/{venta}/detalles', [
        'as' => 'admin.ventas.venta.detalles',
        'uses' => 'VentaController@detalles',
    ]);
    $router->get('ventas/create', [
        'as' => 'admin.ventas.venta.create',
        'uses' => 'VentaController@create',
        'middleware' => 'can:ventas.ventas.create'
    ]);
    $router->post('ventas', [
        'as' => 'admin.ventas.venta.store',
        'uses' => 'VentaController@store',
        'middleware' => 'can:ventas.ventas.create'
    ]);
    $router->post('ventas/pago-credito', [
        'as' => 'admin.ventas.venta.pago_credito',
        'uses' => 'VentaController@pago_credito',
        'middleware' => 'can:ventas.ventas.create'
    ]);
    $router->get('ventas/{venta}/edit', [
        'as' => 'admin.ventas.venta.edit',
        'uses' => 'VentaController@edit',
        'middleware' => 'can:ventas.ventas.edit'
    ]);
    $router->put('ventas/{venta}', [
        'as' => 'admin.ventas.venta.update',
        'uses' => 'VentaController@update',
        'middleware' => 'can:ventas.ventas.edit'
    ]);
    $router->delete('ventas/{venta}', [
        'as' => 'admin.ventas.venta.destroy',
        'uses' => 'VentaController@destroy',
        'middleware' => 'can:ventas.ventas.destroy'
    ]);
    $router->bind('ventadetalle', function ($id) {
        return app('Modules\Ventas\Repositories\VentaDetalleRepository')->find($id);
    });
    $router->get('ventadetalles', [
        'as' => 'admin.ventas.ventadetalle.index',
        'uses' => 'VentaDetalleController@index',
        'middleware' => 'can:ventas.ventadetalles.index'
    ]);
    $router->get('ventadetalles/create', [
        'as' => 'admin.ventas.ventadetalle.create',
        'uses' => 'VentaDetalleController@create',
        'middleware' => 'can:ventas.ventadetalles.create'
    ]);
    $router->post('ventadetalles', [
        'as' => 'admin.ventas.ventadetalle.store',
        'uses' => 'VentaDetalleController@store',
        'middleware' => 'can:ventas.ventadetalles.create'
    ]);
    $router->get('ventadetalles/{ventadetalle}/edit', [
        'as' => 'admin.ventas.ventadetalle.edit',
        'uses' => 'VentaDetalleController@edit',
        'middleware' => 'can:ventas.ventadetalles.edit'
    ]);
    $router->put('ventadetalles/{ventadetalle}', [
        'as' => 'admin.ventas.ventadetalle.update',
        'uses' => 'VentaDetalleController@update',
        'middleware' => 'can:ventas.ventadetalles.edit'
    ]);
    $router->delete('ventadetalles/{ventadetalle}', [
        'as' => 'admin.ventas.ventadetalle.destroy',
        'uses' => 'VentaDetalleController@destroy',
        'middleware' => 'can:ventas.ventadetalles.destroy'
    ]);
// append


});
