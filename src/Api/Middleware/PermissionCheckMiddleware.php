<?php
declare(strict_types=1);

namespace PonHelper\Api\Middleware;

use PonHelper\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Routing\RouteContext;

class PermissionCheckMiddleware implements Middleware
{
    protected $app;
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $perms = $request->getAttribute('auth_info')->group->permissions;
        $requestRuleName = $this->getPermissionNameFromRequest($request);
        if(!$requestRuleName) return  $handler->handle($request);
        if(in_array($requestRuleName, $perms)) {
            return   $handler->handle($request);
        }
        throw new HttpForbiddenException($request, "Insufficient rights to access this resource");
    }
    function getPermissionNameFromRequest(Request $request) {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $pattern = $request->getMethod() . ':' . $route->getPattern();
        foreach ($this->app->conf('api.auth.rules') as $permission) {
            if(!isset($permission['routes'])) continue;
            foreach ($permission['routes'] as $route) {
                if($route === $pattern) {
                    return $permission['key'];
                }
            }
        }
        if($this->app->conf('api.auth.strict_rules')) {
            throw new HttpInternalServerErrorException($request,"Strict rules enabled. Rule not found for route $pattern");
        }
        return  null;
    }

    public function __construct(App $app )
    {
        $this->app = $app;
    }
}
