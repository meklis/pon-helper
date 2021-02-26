<?php
declare(strict_types=1);

namespace PonHelper\Api\Middleware;

use PonHelper\Controllers\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class AuthKeyMiddleware implements Middleware
{
    protected $auth;
    static protected $token;
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = "";
        $tokenHeader = $request->getHeader('X-Auth-Key');
        if(count($tokenHeader) != 0 ) {
            $token = $tokenHeader[0];
        } else if (isset($request->getQueryParams()['x-auth-key'])) {
            $token = $request->getQueryParams()['x-auth-key'];
        } else {
            throw new HttpBadRequestException($request, "X-Auth-Key header not setted. You must set token for private methods.");
        }
        try {
            if (!$this->auth->isKeyValid($token)) {
                throw new HttpUnauthorizedException($request, "Incorrect user token");
            }
        } catch (\Exception $e) {
            throw new HttpUnauthorizedException($request,$e->getMessage());
        }
        $this->auth->updateLastActivity($token);
        $token = $this->auth->getUserByKey($token);
        $request = $request->withAttribute('AUTH_USER',$token);
        return $handler->handle($request);
    }

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
}
