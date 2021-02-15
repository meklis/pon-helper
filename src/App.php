<?php


namespace PonHelper;


use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Monolog\Logger;
use PonHelper\Models\User\User;
use PonHelper\Storage\UserStorage;
use Slim\Factory\AppFactory;
use SwitcherCore\Switcher\CoreConnector;
use SwitcherCore\Switcher\PhpCache;

class App
{
    /**
     * @var self
     */
    static protected $app;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Container
     */
    protected $container;
    public static function init()
    {
        $app = new self();
        self::$app = $app;

        $app->config =require __DIR__ . '/../config/global.php';

        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        if ($app->config['production']) { // Should be set to true in production
            $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
        }
        $containerBuilder->useAnnotations(true);
        $containerBuilder->useAutowiring(true);

        // Set up settings
        $settings = require __DIR__ . '/../app/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $dependencies($containerBuilder);

        // Build PHP-DI Container instance
        /**
         * @var Container
         */
        $container = $containerBuilder->build();
        $container->set(App::class, $app);

        $container->set(CoreConnector::class, function() use ($container) {
            $connector = new \SwitcherCore\Switcher\CoreConnector(\SwitcherCore\Modules\Helper::getBuildInConfig());
            $connector->setLogger($container->get(Logger::class));
            $connector->setCache(new PhpCache());
            return $connector;
        });
        $app->container = $container;
        return $app;
    }



    public function getContainer() {
        return self::$app->container;
    }
    public static function getInstance() {
        return self::$app;
    }
    public function conf($propertyName) {
        $elements = explode(".", $propertyName);
        $arrayKey = join('',array_map(function ($e) {
           return "['{$e}']";
        }, $elements));
        $return = null;
        $evalArrayBlock = "if(isset(\$this->config{$arrayKey})) {\$return = \$this->config{$arrayKey}; }";
        eval($evalArrayBlock);
        return $return;
    }

    /**
     * @return \Slim\App
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function buildSlimApp() {
        // Instantiate the app
        AppFactory::setContainer($this->container);
        $app = AppFactory::create();
        $this->container->set(\Slim\App::class, $app);
        $callableResolver = $app->getCallableResolver();
        // Register middleware
        $middleware = require __DIR__ . '/../app/middleware.php';
        $middleware($app);
        // Register routes
        $routes = require __DIR__ . '/../app/routes.php';
        $routes($app);
        $app->addRoutingMiddleware();
        return $app;
    }


    public function runCommand($command, $arguments = []) {
        $projDir = realpath(__DIR__ . '/../console');
        $cmd = sprintf('/usr/bin/php %s', $command);
        if (count($arguments) > 0) {
            $cmd = sprintf($cmd . ' %s', implode(' ', $arguments));
        }
        exec("$cmd >/dev/null 2>/dev/null &");
        return $this;
    }

    /**
     * @return User
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getSysUser() {
        return $this->container->get(UserStorage::class)->getById(-1);
    }
}