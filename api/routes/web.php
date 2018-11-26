<?php
$router->get('/', function () use ($router) {
    return $router->app->version();
});
  $router->group(['prefix' => 'v1'], function () use ($router) {
  $router->post('remessa', ['uses' => 'APIController@LideryRemessa']);
  $router->post('retorno', ['uses' => 'APIController@LideryRetorno']);
});