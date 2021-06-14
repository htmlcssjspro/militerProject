<?php

use Admin\Controllers\AdminApiController;
use Admin\Controllers\AdminController;
use Main\Controllers\MainApiController;
use Main\Controllers\MainController;

return [
    'main' => [
        'route' => '/(?!api/)(?!admin(?:$|/$|/(?=.+)))',
        'controller' => MainController::class,
    ],
    'api' => [
        'route' => '/api/(?=.+)',
        'controller' => MainApiController::class,
    ],
    'admin' => [
        'route' => '/admin(?:$|/$|/(?=.+))(?!api/)',
        'controller' => AdminController::class,
    ],
    'adminApi' => [
        'route' => '/admin/api/(?=.+)',
        'controller' => AdminApiController::class,
    ],
];
