<?php


namespace PonHelper\Api\Actions\User;

use PonHelper\Models\User\User;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddUserAction extends  UserAction
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
        if(!in_array('group', $keys)) {
            throw new HttpBadRequestException($this->request, "Group is required");
        }
        if(!in_array('password', $keys)) {
            throw new HttpBadRequestException($this->request, "Group is required");
        }
        if(!in_array('login', $keys)) {
            throw new HttpBadRequestException($this->request, "Login is required");
        }
        $user = $this->userStorage->getUserByLogin($data['login']);
        if($user) {
            throw new HttpBadRequestException($this->request, "User with login {$data['login']} already exist");
        }
        $user = new User();
        $user->setName($data['name'])
            ->setLogin($data['login'])
            ->setPassword(sha1($data['password']))
            ->setGroup($this->groupStorage->getById( $data['group']['id']));
        $user = $this->userStorage->add($user);
        return  $this->respondWithData($user->getAsArray());
    }
}