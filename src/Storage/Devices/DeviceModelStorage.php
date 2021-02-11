<?php


namespace PonHelper\Storage\Devices;


use PonHelper\Models\Devices\DeviceModel;
use PonHelper\Storage\AbstractStorage;

/**
 *
 * Class DeviceModelStorage
 * @package PonHelper\Storage\Devices
 */
class DeviceModelStorage extends AbstractStorage
{
    protected $tableName = 'device_models';

    function getByName($name) {
        $id = $this->getOneIdByWhere("name = ? limit 1", [$name]);
        if(!$id) {
            return null;
        }
        return $this->fill((new DeviceModel())->setId($id));
    }

    /**
     * @return DeviceModel[]
     */
    function fetchAll() {
        $psth = $this->pdo->prepare("SELECT id, name, params, vendor, model FROM device_models order by  name");
        $psth->execute();
        $resp = [];
        foreach ($psth->fetchAll() as $e) {
            $resp[] = $this->fillByArr(new DeviceModel(), $e);
        }
        return $resp;
    }

    /**
     * @param $id
     * @return DeviceModel
     */
    function getById($id) {
        return $this->getObjectById(DeviceModel::class, $id);
    }

}