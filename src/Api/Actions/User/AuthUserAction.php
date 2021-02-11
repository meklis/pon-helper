<?php
declare(strict_types=1);

namespace PonHelper\Api\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class AuthUserAction extends UserAction
{

    protected function action(): Response
    {
        $requestBody = $this->getFormData();
        if(!isset($requestBody['username']) || !isset($requestBody['password'])) {
           throw new HttpBadRequestException($this->request, "Username and password fields is required");
        }
        if(!$id = $this->user->auth($requestBody['username'], $requestBody['password'])) {
            throw new HttpUnauthorizedException($this->request, "Incorrect username or password");
        } else {
            $token = $this->user->generateToken($id);
            return $this->respondWithData(['auth_token' => $token, 'info' => $this->user->getUser($id)]);
        }
    }
}
