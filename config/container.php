<?php

use Militer\mvcCore\Csrf\iCsrf;
use Militer\mvcCore\Csrf\Csrf;
use Militer\mvcCore\DI\iContainer;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Request\Request;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\Http\Response\Response;
use Militer\mvcCore\PDO\iDB;
use Militer\mvcCore\PDO\DB;
use Militer\mvcCore\PDO\iPDO;
use Militer\mvcCore\PDO\PDO;
use Militer\mvcCore\Router\iRouter;
use Militer\mvcCore\Router\Router;
use Militer\mvcCore\User\iUser;
use User\User;
use Militer\mvcCore\View\iView;
use Militer\mvcCore\View\View;


$aliases = [
    iContainer::class => Container::class,

    iUser::class => User::class,
    iCsrf::class => Csrf::class,

    iDB::class       => DB::class,
    iPDO::class      => PDO::class,
    iRouter::class   => Router::class,
    iRequest::class  => Request::class,
    iResponse::class => Response::class,
    iView::class     => View::class,
];


$definitions = [
    'config' => function (string $index = NULL) {
        $config = require _ROOT_ . '/config/config.php';
        return $index ? $config[$index] : $config;
    },

    'routes' => function () {
        return require _ROOT_ . '/config/routes.php';
    },

    'dbConfig' => function () {
        return require _ROOT_ . '/config/dbConfig.php';
    },

    'PDO' => function () {
        return Container::get(iPDO::class);
    },

    'pdo' => function () {
        $pdo = Container::get(iDB::class);
        return $pdo::connect();
    },

    'messages' => function (string $index = NULL) {
        $messages = require _ROOT_ . '/config/messages.php';
        return $index ? $messages[$index] : $messages;
    },
    'response' => function (string $index = NULL) {
        $response = require _ROOT_ . '/config/response.php';
        return $index ? $response[$index] : $response;
    },

    'dictionary' => function (string $index = NULL) {
        $dictionary = require _ROOT_ . '/config/dictionary.php';
        return $index ? $dictionary[$index] : $dictionary;
    },
];

$sets = array_merge($aliases, $definitions);
Container::sets($sets);
