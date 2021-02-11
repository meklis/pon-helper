<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use PonHelper\App;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function () {
            $loggerSettings = App::getInstance()->conf('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
       PDO::class => function () {
           $sql = new PDO(_env('DATABASE_URL'), _env('DATABASE_USER'), _env('DATABASE_PASSWD'), [
               PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
               PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
               PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
               PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
               PDO::ATTR_PERSISTENT    => false,
           ]);
           $sql->exec("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
           return $sql;
       },
    ]);

};
