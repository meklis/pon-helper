<?php


namespace PonHelper\Api\Actions\UserGroup;

use PonHelper\Models\User\UserGroup;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateGroupUserAction extends  GroupAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     */

    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $data = $this->getFormData();
        $group = $this->groupStorage->fill((new UserGroup())->setId($id));
        if(isset($data['name'])) {
            $group->setName($data['name']);
        }
        if(isset($data['display'])) {
            $group->setDisplay($data['display']);
        }
        if(isset($data['permissions'])) {
            $group->setPermissions($data['permissions']);
        }
        $permissions = $this->groupStorage->update($group);
        return  $this->respondWithData($permissions->getAsArray());
    }
}