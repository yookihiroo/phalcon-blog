<?php

error_reporting(E_ALL);

define('APP_PATH', realpath('..'));

require(APP_PATH. '/vendor/autoload.php');
$dotenv = new Dotenv\Dotenv(APP_PATH);
$dotenv->load();

define('DEBUG', getenv('DEBUG'));
if (DEBUG) {
    set_error_handler(
        function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        }
    );
    $debug = new \Phalcon\Debug();
    $debug->listen();
}

$app = function() {
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
    if (DEBUG) {
        $di['app'] = $application;
        (new Snowair\Debugbar\ServiceProvider('debugbar'))->start();
    }
    echo $application->handle()->getContent();
};

if (DEBUG) {
    $app();
} else {
    try {
        $app();
    }  catch (\Exception $e) {
        echo $e->getMessage() . '<br>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }
}
