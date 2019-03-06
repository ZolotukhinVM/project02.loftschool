<?php
require './vendor/autoload.php';
require './config.php';
require './Eloquent/config.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();
session_start();
// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
