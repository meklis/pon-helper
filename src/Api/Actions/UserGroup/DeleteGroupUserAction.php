<?php


namespace PonHelper\Api\Actions\UserGroup;

use PonHelper\Models\User\UserGroup;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteGroupUserAction extends  GroupAction
{

    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $this->groupStorage->delete((new UserGroup())->setId($id));
        return  $this->respondWithData(true);
    }
}