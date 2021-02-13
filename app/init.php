<?php
declare(strict_types=1);

use PonHelper\App;

error_reporting(E_ALL);
set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
});

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/helpers/global.funcs.php';

//Load envs
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$app = App::init();