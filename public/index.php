<?php

use PonHelper\Api\Handlers\HttpErrorHandler;
use PonHelper\Api\Handlers\ShutdownHandler;
use PonHelper\Api\ResponseEmitter\ResponseEmitter;
use PonHelper\App;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../app/init.php';

$container = App::getInstance()->getContainer();

$container->set('errorHandler', function ($c) use ($container) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withStatus($exception->getCode())
            ->withHeader('Content-Type', 'application/json')
            ->write($exception->getMessage());
    };
}
);

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(Slim\App::class, $app);
$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
$errorHandler->setDisplayStackTrace($container->get('settings')['stackTraceInErrorResponse']);
$logger = $app->getContainer()->get(\Monolog\Logger::class);
$errorHandler->setLogger($logger);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler( $request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);
// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
