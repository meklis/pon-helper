<?php


namespace PonHelper\Api\Actions\UserGroup;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\UserGroupStorage;

abstract class GroupAction extends Action
{
    /**
     * @Inject
     * @var UserGroupStorage
     */
    protected $groupStorage;

}