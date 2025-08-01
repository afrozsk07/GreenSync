<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Vercel-specific configurations
if (getenv('VERCEL_ENV') === 'production') {
    // Set production environment variables
    putenv('APP_ENV=production');
    putenv('APP_DEBUG=false');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Handle the request
$request = Request::capture();
$response = $app->handleRequest($request);

// Send the response
$response->send();
//debug comment
// Terminate the application
$app->terminate($request, $response); 