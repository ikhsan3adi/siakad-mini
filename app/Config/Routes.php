<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes,  ['except' => ['login', 'logout']]);

$routes->get('login', 'LoginController::loginView');
$routes->post('login', 'LoginController::loginAction');
$routes->get('logout', 'LoginController::logoutAction');

$routes->group('', ['filter' => 'cookiejwt'], static function (RouteCollection $routes) {
    $routes->get('/', 'Home::index');
});
