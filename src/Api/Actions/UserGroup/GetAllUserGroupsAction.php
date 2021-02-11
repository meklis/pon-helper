<?php


namespace PonHelper\Api\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;

class GetAllUserGroupsAction extends  GroupAction
{
    protected function action(): Response
    {
        $groups = $this->groupStorage->fetchAll();
        $response = [];
        foreach ($groups as $user) {
            $response[] = $user->getAsArray();
        }
        return  $this->respondWithData($response);
    }
}