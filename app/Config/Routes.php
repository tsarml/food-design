<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/login',          'AuthController::loginForm');
$routes->post('/login',         'AuthController::login');
$routes->get('/register',       'AuthController::registerForm');  
$routes->get('/logout',         'AuthController::logout');

$routes->get('/',               'HomeController::index');
$routes->post('/swipe',         'HomeController::swipe');          

$routes->get('/add-food',       'FoodController::create');
$routes->post('/add-food',      'FoodController::store');

$routes->get('/stats',         'StatsController::index');      