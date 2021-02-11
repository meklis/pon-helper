<?php


namespace PonHelper\Storage\Devices;


use PonHelper\Models\Devices\DeviceAccess;
use PonHelper\Storage\AbstractStorage;

/**
 * Class DeviceAccessStorage
 * @package PonHelper\Storage\Devices
 */
class DeviceAccessStorage extends AbstractStorage
{
    protected $tableName = 'device_accesses';
    /**
     * @return DeviceAccess[]
     */
    function fetchAll() {
        $psth = $this->pdo->prepare("SELECT id, name, community, login, password FROM device_accesses order by  name");
        $psth->execute();
        $resp = [];
        foreach ($psth->fetchAll() as $e) {
            $resp[] = $this->fillByArr(new DeviceAccess(), $e);
        }
        return $resp;
    }

    /**
     * @param $id
     * @return DeviceAccess
     */
    function getById($id) {
        return $this->getObjectById(DeviceAccess::class, $id);
    }

}