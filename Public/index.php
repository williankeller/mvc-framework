<?php
// Display stage errors.
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
 * Define Application Configuration Constants
 */
// Base root directory.
define('BASE_DIR', str_replace('\\', '/', dirname(__DIR__)));
// Base Application directory.
define('APPLICATION', BASE_DIR . '/Application');
// Base Public directory.
define('PUBLIC_PATH', BASE_DIR . '/Public');

/*
 * Autoload
 * Autoloader file created.
 */
try {
    // Include the autoloader.
    require '../Application/Core/Autoloader.php';

    $loader = new \Application\Core\Autoloader();
    $loader->register();
}
catch (\Exception $exception) {
    die($exception);
}

/*
 * Start Session
 */
\Application\Core\Storage\Session::init();

/**
 * Create the application instance which will take care of routing the incoming
 * request to the corresponding controller and action method if valid
 */
$app = new \Application\Core\Registry();

define('PUBLIC_ROOT', $app->request->root());

/**
 * Run The Application
 * Handle the incoming request and send a response back to the client's browser.
 */
$app->run();
