<?php


namespace PonHelper\Storage;


use DI\Annotation\Inject;
use InvalidArgumentException;
use PonHelper\Models\SystemModule;
use PonHelper\Models\User\User;

class SystemModuleStorage extends AbstractStorage
{
    protected $tableName = 'system_modules';


    /**
     * @param $id
     * @return SystemModule
     */
    function getById($id) {
        return $this->getObjectById(SystemModule::class, $id);
    }

    /**
     * @return SystemModule[]
     */
    function fetchAll() {
        $psth = $this->pdo->query("SELECT * FROM system_modules order by `key` desc ");
        $response = [];
        foreach ($psth->fetchAll() as $r) {
            $obj= $this->fillByArr(new SystemModule(), $r);
            $obj->builtIn = $obj->builtIn === 1;
            $obj->enabled = $obj->enabled === 1;
            $response[] = $obj;
        }
        return $response;
    }
}