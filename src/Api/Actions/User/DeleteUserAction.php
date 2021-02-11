<?php


namespace PonHelper\Api\Actions\User;

use PonHelper\Models\User\User;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserAction extends  UserAction
{

    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $this->userStorage->delete((new User())->setId($id));
        return  $this->respondWithData(true);
    }
}