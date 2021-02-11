<?php


namespace PonHelper\Api\Actions\Auth;


use DI\Annotation\Inject;
use Exception;
use PonHelper\Api\Actions\Action;
use PonHelper\Controllers\Auth;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UserAuthAction extends Action
{
    /**
     * @Inject
     * @var Auth
     */
    protected $auth;

    protected function action(): Response
    {
        $data = $this->getFormData();
        if(!isset($data['login']) || !isset($data['password'])) {
            throw new HttpBadRequestException($this->request, "Login and password are required fields");
        }
        $user = null;
        try {
            $user = $this->auth->checkPair($data['login'], $data['password']);
        } catch (Exception $e) {
            throw new HttpUnauthorizedException($this->request, $e->getMessage());
        }
        $key = $this->auth->generateKey($user);
        return  $this->respondWithData($key->getAsArray());
    }

}