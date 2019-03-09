<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/presupuestos'], function (Router $router) {
    $router->bind('presupuesto', function ($id) {
        return app('Modules\Presupuestos\Repositories\PresupuestoRepository')->find($id);
    });
    $router->get('presupuestos', [
        'as' => 'admin.presupuestos.presupuesto.index',
        'uses' => 'PresupuestoController@index',
        'middleware' => 'can:presupuestos.presupuestos.index'
    ]);
    $router->get('presupuestos/index-ajax', [
        'as' => 'admin.presupuestos.presupuesto.index_ajax',
        'uses' => 'PresupuestoController@index_ajax',
        'middleware' => 'can:presupuestos.presupuestos.index'
    ]);
    $router->get('presupuestos/create', [
        'as' => 'admin.presupuestos.presupuesto.create',
        'uses' => 'PresupuestoController@create',
        'middleware' => 'can:presupuestos.presupuestos.create'
    ]);
    $router->post('presupuestos', [
        'as' => 'admin.presupuestos.presupuesto.store',
        'uses' => 'PresupuestoController@store',
        'middleware' => 'can:presupuestos.presupuestos.create'
    ]);
    $router->get('presupuestos/{presupuesto}/edit', [
        'as' => 'admin.presupuestos.presupuesto.edit',
        'uses' => 'PresupuestoController@edit',
        'middleware' => 'can:presupuestos.presupuestos.edit'
    ]);
    $router->put('presupuestos/{presupuesto}', [
        'as' => 'admin.presupuestos.presupuesto.update',
        'uses' => 'PresupuestoController@update',
        'middleware' => 'can:presupuestos.presupuestos.edit'
    ]);
    $router->delete('presupuestos/{presupuesto}', [
        'as' => 'admin.presupuestos.presupuesto.destroy',
        'uses' => 'PresupuestoController@destroy',
        'middleware' => 'can:presupuestos.presupuestos.destroy'
    ]);
    $router->bind('presupuestodetalle', function ($id) {
        return app('Modules\Presupuestos\Repositories\PresupuestoDetalleRepository')->find($id);
    });
    $router->get('presupuestodetalles', [
        'as' => 'admin.presupuestos.presupuestodetalle.index',
        'uses' => 'PresupuestoDetalleController@index',
        'middleware' => 'can:presupuestos.presupuestodetalles.index'
    ]);
    $router->get('presupuestodetalles/create', [
        'as' => 'admin.presupuestos.presupuestodetalle.create',
        'uses' => 'PresupuestoDetalleController@create',
        'middleware' => 'can:presupuestos.presupuestodetalles.create'
    ]);
    $router->post('presupuestodetalles', [
        'as' => 'admin.presupuestos.presupuestodetalle.store',
        'uses' => 'PresupuestoDetalleController@store',
        'middleware' => 'can:presupuestos.presupuestodetalles.create'
    ]);
    $router->get('presupuestodetalles/{presupuestodetalle}/edit', [
        'as' => 'admin.presupuestos.presupuestodetalle.edit',
        'uses' => 'PresupuestoDetalleController@edit',
        'middleware' => 'can:presupuestos.presupuestodetalles.edit'
    ]);
    $router->put('presupuestodetalles/{presupuestodetalle}', [
        'as' => 'admin.presupuestos.presupuestodetalle.update',
        'uses' => 'PresupuestoDetalleController@update',
        'middleware' => 'can:presupuestos.presupuestodetalles.edit'
    ]);
    $router->delete('presupuestodetalles/{presupuestodetalle}', [
        'as' => 'admin.presupuestos.presupuestodetalle.destroy',
        'uses' => 'PresupuestoDetalleController@destroy',
        'middleware' => 'can:presupuestos.presupuestodetalles.destroy'
    ]);
    $router->get('presupuestos/exportar', [
        'as' => 'admin.presupuestos.presupuesto.exportar',
        'uses' => 'PresupuestoController@export_to_pdf',
    ]);
// append


});
