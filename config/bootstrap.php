<?php

//* Directories for PHP
define('_ROOT_', dirname(__DIR__));

define('MAIN_CONTROLLERS',  _ROOT_ . '/Main/Controllers');
define('MAIN_MODELS',       _ROOT_ . '/Main/Models');
define('MAIN_VIEWS',        _ROOT_ . '/Main/Views');

define('ADMIN_CONTROLLERS', _ROOT_ . '/Admin/Controllers');
define('ADMIN_MODELS',      _ROOT_ . '/Admin/Models');
define('ADMIN_VIEWS',       _ROOT_ . '/Admin/Views');

define('ERROR_LOG_FILE', _ROOT_ . '/log/errors.log');

define('_PUBLIC_', _ROOT_ . '/public');
define('PAGE_404', _PUBLIC_ . '/404.php');
define('PAGE_400', _PUBLIC_ . '/400.php');

//* Directories for pages & ajax
define('UPLOADS', '/public/uploads');

define('API',  '/api/v1');



define('DEV', true); // Установить в false на production
// DEV ? error_reporting(E_ALL) : error_reporting(0);

if (DEV) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    ini_set('error_reporting', -1);
    error_reporting(E_ALL);
    require _ROOT_ . '/config/devHelper.php';
} else {
    error_reporting(0);
}
