<?php


namespace PonHelper\Console;


use DI\Annotation\Inject;
use PonHelper\App;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RoutesListCommand extends AbstractCommand
{
    /**
     * @Inject
     * @var App
     */
    protected $app;

    protected function configure()
    {
        $this->setName("router:list")
            ->setDescription("List of routes")
        ->addOption("perms")
        ->addOption("yaml")
        ->addOption("rules")
        ;

    }
    function execute(InputInterface $input, OutputInterface $output)
    {
        $slim = $this->app->buildSlimApp();
        $routes = [];
        foreach ($slim->getRouteCollector()->getRoutes() as $route) {
            foreach ($route->getMethods() as $method) {
                if(!is_string($route->getCallable())) {
                    continue;
                }
                $routes["{$method}:{$route->getPattern()}"] = [
                    'method' => $method,
                    'pattern' => $route->getPattern(),
                    'callable' => $route->getCallable(),
                ];
                if($input->getOption('perms')) {

                    $routes["{$method}:{$route->getPattern()}"]['rule'] = '<NOT SETTED>';
                    $routes["{$method}:{$route->getPattern()}"]['description'] = '<NOT SETTED>';
                }
            }
        }
        ksort($routes);
        if($input->getOption('perms')) {
            $rules = $this->app->conf('api.auth.rules');
            foreach ($rules as $rule) {
                if(isset($rule['routes'])) {
                    foreach ($rule['routes'] as $route) {
                        if(isset($routes[$route])) {
                            $routes[$route]['rule'] = $rule['key'];
                            $routes[$route]['description'] = $rule['descr'];
                        }
                    }
                }
            }
        }
        if($input->getOption('rules')) {
            $rules = [];
            foreach ($routes as $key=>$route) {
                $rules[$route['rule']]['key'] = $route['rule'];
                $rules[$route['rule']]['descr'] = $route['description'];
                $rules[$route['rule']]['routes'][] = $key;
            }
            $routes = $rules;
        }
        $routes = array_values($routes);
        if($input->getOption('yaml')) {
            $output->writeln($this->toYaml($routes));
        } else {
            $output->writeln($this->toJson($routes));
        }
        return self::SUCCESS;
    }
}