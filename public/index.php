<?php

// use Militer\mvcCore\App;
use Militer\mvcCore\Exception\CustomException;
use Militer\mvcCore\Router\Router;

define('MILITER_START', microtime(true));

session_start();

// $_SESSION['count'] = isset($_SESSION['count']) ? (++$_SESSION['count']) : 0;

require dirname(__DIR__) . '/config/bootstrap.php';

require _ROOT_ . '/vendor/autoload.php';

CustomException::init();

require _ROOT_ . '/config/container.php';

// App::start();

Router::init();
