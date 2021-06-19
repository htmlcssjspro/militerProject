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
    'config' => function (string $index = null, string $name = null) {
        $config = require _CONFIG_ . '/config.php';
        $config = $index ? $config[$index] : $config;
        $config = $index && $name ? $config[$name] : $config;
        return $config;
    },

    'dbConfig' => function () {
        return require _CONFIG_ . '/dbConfig.php';
    },

    'PDO' => function () {
        return Container::get(iPDO::class);
    },

    'response' => function (string $index = null) {
        $response = require _CONFIG_ . '/response.php';
        return $index ? $response[$index] : $response;
    },

    // 'dictionary' => function (string $index = null) {
    //     $dictionary = require _CONFIG_ . '/dictionary.php';
    //     return $index ? $dictionary[$index] : $dictionary;
    // },

    // 'email' => function (string $index = null) {
    //     $email = require _CONFIG_ . '/email.php';
    //     return $index ? $email[$index] : $email;
    // },

    // 'pdo' => function () {
    //     $pdo = Container::get(iDB::class);
    //     return $pdo::connect();
    // },

];

$sets = array_merge($aliases, $definitions);
Container::sets(array_merge($aliases, $definitions));
