<?php


namespace PonHelper\Api\Actions\UserGroup;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\App;
use Psr\Http\Message\ResponseInterface as Response;

class PermissionListAction extends Action
{
    /**
     * @Inject
     * @var App
     */
    protected $app;
    /**
     * @return Response
     */
    protected function action(): Response
    {
        $permissions = [];
        foreach ($this->app->conf('api.auth.rules') as $perm) {
            $permissions[] = [
              'key' => $perm['key'],
              'name' => $perm['name'],
            ];
        }
        return $this->respondWithData($permissions);
    }

}