<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');

// Auth Routes
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/store', 'Auth::store');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/auth/logout', 'Auth::logout');

// Forum Routes
$routes->get('/forum', 'Forum::index');
$routes->get('/forum/create', 'Forum::create');
$routes->post('/forum/store', 'Forum::store');
$routes->get('/forum/show/(:num)', 'Forum::show/$1');
$routes->post('/forum/storeReply/(:num)', 'Forum::storeReply/$1');
$routes->get('/forum/delete/(:num)', 'Forum::delete/$1');
