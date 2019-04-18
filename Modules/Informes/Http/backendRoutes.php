<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/informes'], function (Router $router) {
    $router->bind('informe', function ($id) {
        return app('Modules\Informes\Repositories\InformeRepository')->find($id);
    });
    $router->get('informes', [
        'as' => 'admin.informes.informe.index',
        'uses' => 'InformeController@index',
        'middleware' => 'can:informes.informes.index'
    ]);
    $router->get('ger-per-day', [
        'as' => 'admin.informes.informe.perDayAjax',
        'uses' => 'InformeController@getPerDayAjax',
        'middleware' => 'can:informes.informes.index'
    ]);
    $router->get('informes/anual', [
        'as' => 'admin.informes.informe.exportAnual',
        'uses' => 'InformeController@exportAnual',
        'middleware' => 'can:informes.informes.index'
    ]);
// append

});
