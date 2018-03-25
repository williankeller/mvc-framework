<?php

/*
  |--------------------------------------------------------------------------
  | Autoload
  |--------------------------------------------------------------------------
  |
  | Autoloader file created.
  |
 */

try {
    // Include the autoloader.
    require '../Application/Core/Autoloader.php';

    $srcBaseDirectory = dirname(dirname(__FILE__));

    $loader = new \Application\Core\Autoloader(null, $srcBaseDirectory);
    $loader->register();
}
catch (\Exception $exc) {
    echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
        <h3 style="margin:0;font-size:1.7em;font-weight:normal;text-transform:none;text-align:center;margin:50px;color:#2f2f2f;">
        Getting updated, try to load page again.</h3>
    </div>
</div>
HTML;
    exit(1);
}
/*
  |--------------------------------------------------------------------------
  | Define Application Configuration Constants
  |--------------------------------------------------------------------------
  |
 */

define('BASE_DIR', str_replace("\\", "/", dirname(__DIR__)));
define('IMAGES', str_replace("\\", "/", __DIR__) . "/img/");
define('APP', BASE_DIR . "/app/");

/*
  |--------------------------------------------------------------------------
  | Start Session
  |--------------------------------------------------------------------------
  |
 */
\Application\Core\Session::init();

/*
  |--------------------------------------------------------------------------
  | Create The Application
  |--------------------------------------------------------------------------
  |
  | Create the application instance which will take care of routing the incoming
  | request to the corresponding controller and action method if valid
  |
 */

$app = new \Application\Core\Registry();

// Config::set('root', $app->request->root());
define('PUBLIC_ROOT', $app->request->root());

/*
  |--------------------------------------------------------------------------
  | Run The Application
  |--------------------------------------------------------------------------
  |
  | Handle the incoming request and send a response back to the client's browser.
  |
 */

$app->run();
