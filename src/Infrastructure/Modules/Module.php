<?php


namespace PonHelper\Infrastructure\Modules;


use DI\Annotation\Inject;
use PonHelper\Storage\SystemModuleStorage;

abstract  class Module
{
    /**
     * @Inject
     * @return SystemModuleStorage
     */
    protected $moduleStorage;

    /**
     * @Inject
     * @var \PDO
     */
    protected $pdo;

    abstract function install();
    abstract function deinstall();
    abstract function enable();
    abstract function disable();
    abstract function init();

    function registerEvents() {

    }

    function __construct()
    {

    }

    function readConfig() {

    }
}