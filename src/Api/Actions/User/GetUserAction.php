<?php


namespace PonHelper\Api\Actions\User;

use DI\Annotation\Inject;
use PonHelper\Models\User\UserAuthKey;
use PonHelper\Storage\UserAuthKeyStorage;
use Psr\Http\Message\ResponseInterface as Response;

class GetUserAction extends  UserAction
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
        $id = $this->request->getAttribute('id');
        $user = $this->userStorage->getById($id);
        $u = $user->getAsArray();
        $auth = $this->userAuthStorage->getLastActivityByUser($user);
        if($auth !== null) {
            $a = $auth->getAsArray();
            unset($a['key']);
            unset($a['user']);
            $u['last_activity'] = $auth->getLastActivity();
        }
        $u['active_sessions'] = [];
        $sessions = array_filter($this->userAuthStorage->getSessionsByUser($user), function ($el) {
            return $el->getStatus() === UserAuthKey::STATUS_ACTIVE;
        });
        foreach ($sessions as $ses) {
            $s = $ses->getAsArray();
            unset($s['user']);
            unset($s['key']);
            $u['active_sessions'][] = $s;
        }
        return  $this->respondWithData($u);
    }
}