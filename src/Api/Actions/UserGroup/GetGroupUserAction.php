<?php


namespace PonHelper\Api\Actions\UserGroup;

use Psr\Http\Message\ResponseInterface as Response;

class GetGroupUserAction extends  GroupAction
{
    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $group = $this->groupStorage->getById($id);
        return  $this->respondWithData($group->getAsArray());
    }
}