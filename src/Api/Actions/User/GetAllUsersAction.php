<?php


namespace PonHelper\Api\Actions\User;

use DI\Annotation\Inject;
use PonHelper\Storage\UserAuthKeyStorage;
use Psr\Http\Message\ResponseInterface as Response;

class GetAllUsersAction extends  UserAction
{

    /**
     * @Inject
     * @var UserAuthKeyStorage
     */
    protected $userAuthStorage;
    /**
     * @return Response
     */

    protected function action(): Response
    {
        $users = $this->userStorage->fetchAll();
        $response = [];

        foreach ($users as $user) {
            $u = $user->getAsArray();
            $auth = $this->userAuthStorage->getLastActivityByUser($user);
            if($auth !== null) {
                $u['last_activity'] = $auth->getLastActivity();
            }
            $response[] = $u;
        }
        return  $this->respondWithData($response);
    }
}