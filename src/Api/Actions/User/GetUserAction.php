<?php


namespace PonHelper\Api\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class GetUserAction extends  UserAction
{
    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $user = $this->userStorage->getById( $id);
        return  $this->respondWithData($user);
    }
}