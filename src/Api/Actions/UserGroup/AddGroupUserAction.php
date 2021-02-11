<?php


namespace PonHelper\Api\Actions\UserGroup;

use PonHelper\Models\User\UserGroup;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddGroupUserAction extends  GroupAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     */

    protected function action(): Response
    {
        $data = $this->getFormData();
        $keys = array_keys($data);
        if(!in_array('name', $keys)) {
            throw new HttpBadRequestException($this->request, "Name is required");
        }
        if(!in_array('display', $keys)) {
            throw new HttpBadRequestException($this->request, "Display is required");
        }
        if(!in_array('permissions', $keys)) {
            throw new HttpBadRequestException($this->request, "Permissions is required");
        }
        $obj = new UserGroup();
        $obj->setName($data['name'])
            ->setDisplay($data['display'])
            ->setPermissions($data['permissions']);
        $user = $this->groupStorage->add($obj);
        return  $this->respondWithData($user->getAsArray());
    }
}