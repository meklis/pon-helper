<?php


namespace PonHelper\Controllers;


use DI\Annotation\Inject;
use PonHelper\App;
use PonHelper\Models\SystemAction;
use PonHelper\Models\User\User;
use PonHelper\Storage\SystemActionsStorage;

class ActionLogger
{
    /**
     * @Inject
     * @var SystemActionsStorage
     */
    protected $actionStorage;
    /**
     * @Inject
     * @var App
     */
    protected $app;

    /**
     * @param $actionName
     * @param $message
     * @param $status
     * @param User $user
     * @param null $meta
     * @return SystemAction
     */
    function add($actionName, $message, $status, User $user, $meta = null) {
        $action = new SystemAction();
        $action->setAction($actionName)
            ->setMessage($message)
            ->setStatus($status)
            ->setUser($user)
            ->setMeta($meta);
        return $this->actionStorage->add($action);
    }
    /**
     * @param $actionName
     * @param $message
     * @param $status
     * @param User $user
     * @param null $meta
     * @return SystemAction
     */
    function addSystemUser($actionName, $message, $status,  $meta = null) {
        $action = new SystemAction();
        $action->setAction($actionName)
            ->setMessage($message)
            ->setStatus($status)
            ->setUser($this->app->getSysUser())
            ->setMeta($meta);
        return $this->actionStorage->add($action);
    }
}