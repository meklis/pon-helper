<?php


namespace PonHelper\Api\Actions\User;

use PonHelper\Models\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateUserAction extends  UserAction
{

    /**
     * @return Response
     * @throws HttpBadRequestException
     */

    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $data = $this->getFormData();

        $user = $this->userStorage->fill((new User())->setId($id));
        if(isset($data['name'])) {
            $user->setName($data['name']);
        }
        if(isset($data['login'])) {
            $user->setLogin($data['login']);
        }
        if(isset($data['password'])) {
            $user->setPassword(sha1($data['password']));
        }
        if(isset($data['group']['id'])) {
            $group = $this->groupStorage->getById($data['group']['id']);
            $user->setGroup($group);
        }
        $user = $this->userStorage->update($user);
        return  $this->respondWithData($user->getAsArray());
    }
}