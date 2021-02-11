<?php


namespace PonHelper\Api\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class GetAllUsersAction extends  UserAction
{
    protected function action(): Response
    {
        $users = $this->userStorage->fetchAll();
        $response = [];
        foreach ($users as $user) {
            $response[] = $user->getAsArray();
        }
        return  $this->respondWithData($response);
    }
}