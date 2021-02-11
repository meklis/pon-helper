<?php
declare(strict_types=1);

use PonHelper\Api\Middleware\CorsMiddleware;
use PonHelper\Api\Middleware\SessionMiddleware;
use Slim\App;
return function (App $app) {
    $app->add(CorsMiddleware::class);
    $app->add(SessionMiddleware::class);
};
