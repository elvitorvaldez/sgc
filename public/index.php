<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

//Definir ruta de aplicacion
define('APPLICATION_PATH',
              dirname(__FILE__) . '/../module/Application');

//definir ambiente de aplicacion

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

// Setup autoloading
require 'init_autoloader.php';
/** Zend_Application */


// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
