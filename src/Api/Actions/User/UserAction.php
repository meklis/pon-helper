<?php


namespace PonHelper\Api\Actions\User;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\UserGroupStorage;
use PonHelper\Storage\UserStorage;

abstract class UserAction extends Action
{
    /**
     * @Inject
     * @var UserStorage
     */
    protected $userStorage;
    /**
     * @Inject
     * @var UserGroupStorage
     */
    protected $groupStorage;

}