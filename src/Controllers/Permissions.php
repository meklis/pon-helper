<?php


namespace PonHelper\Controllers;


use PonHelper\App;
use PonHelper\Models\Permission;

class Permissions
{
    protected $rules;
    function __construct(App $app) {
        $this->rules = $app->conf('api.auth.rules');
    }
    function getPermissions() {
        $perms = [];
        foreach ($this->rules as $perm) {
            $perms[] = new Permission($perm['key'], $perm['name'], $perm['routes']);
        }
        return $perms;
    }

}