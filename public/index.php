<?php

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));

define('DEBUG', true);
require(APP_PATH. '/vendor/autoload.php');

try {

    /**
     * Read the configuration
     */
    $config = include APP_PATH . "/app/config/config.php";

    /**
     * Read auto-loader
     */
    include APP_PATH . "/app/config/loader.php";

    /**
     * Read services
     */
    include APP_PATH . "/app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    if (defined('DEBUG')) {
        $di['app'] = $application;
        (new Snowair\Debugbar\ServiceProvider())->start();
    }
    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
