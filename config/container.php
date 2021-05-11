<?php

use Militer\mvcCore\Csrf\Csrf;
use Militer\mvcCore\Csrf\iCsrf;
use Militer\mvcCore\DB\DB;
use Militer\mvcCore\DB\iDB;
use Militer\mvcCore\DI\Container;
use Militer\mvcCore\DI\iContainer;
use Militer\mvcCore\Http\Request\iRequest;
use Militer\mvcCore\Http\Request\Request;
use Militer\mvcCore\Http\Response\iResponse;
use Militer\mvcCore\Http\Response\Response;
use Militer\mvcCore\Router\iRouter;
use Militer\mvcCore\Router\Router;
use Militer\mvcCore\User\iUser;
use Militer\mvcCore\View\iView;
use Militer\mvcCore\View\View;
use User\User;

$aliases = [
    iUser::class => User::class,
    iCsrf::class => Csrf::class,

    iContainer::class  => Container::class,
    iDB::class         => DB::class,
    iRouter::class     => Router::class,
    iRequest::class    => Request::class,
    iResponse::class   => Response::class,
    iView::class       => View::class,
];


$definitions = [

    'config' => function ($index = NULL) {
        $config = require _ROOT_ . '/config/config.php';
        return $index ? $config[$index] : $config;
    },

    'dbConfig' => function () {
        return require _ROOT_ . '/config/dbConfig.php';
    },

    'pdo' => function () {
        $db = Container::get(iDB::class);
        return $db::connect();
    },

    'messages' => function ($index = NULL) {
        $messages = require _ROOT_ . '/config/messages.php';
        return $index ? $messages[$index] : $messages;
    },

    'dictionary' => function ($index = NULL) {
        $dictionary = require _ROOT_ . '/config/dictionary.php';
        return $index ? $dictionary[$index] : $dictionary;
    },


];

$sets = array_merge($aliases, $definitions);
Container::sets($sets);
