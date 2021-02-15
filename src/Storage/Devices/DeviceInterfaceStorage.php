<?php


namespace PonHelper\Storage\Devices;


use DI\Annotation\Inject;
use Exception;
use PonHelper\Models\Devices\Device;
use PonHelper\Models\Devices\DeviceAccess;
use PonHelper\Models\Devices\DeviceInterface;
use PonHelper\Models\Devices\DeviceModel;
use PonHelper\Storage\AbstractStorage;

/**
 * Class DeviceStorage
 * @package PonHelper\Storage\Devices
 */
class DeviceInterfaceStorage extends AbstractStorage
{

    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $deviceStorage;


    protected $tableName = 'device_interfaces';

    /**
     * @param DeviceInterface $object
     * @return DeviceInterface
     * @throws Exception
     */
    public function fill($object)
    {
        $fill = parent::fill($object); // TODO: Change the autogenerated stub
        $fill->device = $this->deviceStorage->getById($fill->device_id);
        return $fill;
    }

    /**
     * @param DeviceInterface $object
     * @return DeviceInterface
     */
    public function add($object)
    {
        $object->device_id = $object->getDevice()->getId();
        if($object->getParent()) {
            $object->parent_id = $object->getParent()->getId();
        }
        return parent::add($object); // TODO: Change the autogenerated stub
    }

    /**
     * @param DeviceInterface $object
     * @return DeviceInterface
     */
    public function update($object)
    {
        $object->updated_at = date("Y-m-d H:i:s");
        $object->device_id = $object->getDevice()->getId();
        if($object->getParent()) {
            $object->parent_id = $object->getParent()->getId();
        }
        return parent::update($object); // TODO: Change the autogenerated stub
    }

    /**
     * @return DeviceInterface[]
     */
    function fetchAll()
    {
        $psth = $this->pdo->prepare("SELECT id, `index`, name, device_id, created_at, updated_at, detailed, type, parent_id FROM device_interfaces order by  name, id desc");
        $psth->execute();
        $resp = [];
        foreach ($psth->fetchAll() as $e) {
            $devInterface = $this->fillByArr(new DeviceInterface(), $e);
            $devInterface->setDevice($this->deviceStorage->fill((new Device())->setId($devInterface->device_id)));
            $resp[] = $devInterface;
        }

        return $resp;
    }

    /**
     * @param $id
     * @return DeviceInterface
     */
    function getById($id)
    {
        return $this->getObjectById(DeviceInterface::class, $id);
    }

    /**
     * @param DeviceInterface $deviceInterface
     * @return DeviceInterface
     */
    function fillChildren(DeviceInterface $deviceInterface) {
        $psth = $this->pdo->prepare("SELECT id, `index`, name, device_id, created_at, updated_at, detailed, type, parent_id 
        FROM device_interfaces WHERE parent_id = ? order by  name, id desc");
        $psth->execute([$deviceInterface->getId()]);
        $deviceInterface->children = [];
        foreach ($psth->fetchAll() as $e) {
            $child = $this->fillByArr(new DeviceInterface(), $e);
            $child->setDevice($deviceInterface->getDevice());
            $deviceInterface->children[] = $child;
        }
        return $deviceInterface;
    }
    /**
     * @param Device $device
     * @return DeviceInterface[]
     */
    function getRootInterfaces(Device $device) {
        $psth = $this->pdo->prepare("SELECT id, `index`, name, device_id, created_at, updated_at, detailed, type, parent_id 
        FROM device_interfaces WHERE device_id = ? and (parent_id is null or parent_id <= 0) order by  name, id desc");
        $psth->execute([$device->getId()]);
        $resp = [];
        foreach ($psth->fetchAll() as $e) {
            $resp = $this->fillByArr(new DeviceInterface(), $e);
            $resp->setDevice($device);
        }
        return $resp;
    }

    /**
     * @param DeviceInterface $object
     * @return DeviceInterface
     */
    function updateOnDuplicate($object)
    {
        $object->device_id = $object->getDevice()->getId();
        $object->setUpdatedAt(date("Y-m-d H:i:s"));
        if($parent = $object->getParent()) {
            $object->parent_id = $parent->getId();
        }
        return parent::updateOnDuplicate($object); // TODO: Change the autogenerated stub
    }

    /**
     * @param Device $device
     * @return array
     */
    function getByDevice(Device $device)
    {
        $psth = $this->pdo->prepare("SELECT id, `index`, name, device_id, created_at, updated_at, detailed, type, parent_id 
        FROM device_interfaces  WHERE device_id = ? order by  name, id desc");
        $psth->execute([$device->getId()]);
        $resp = [];
        foreach ($psth->fetchAll() as $e) {
            $devInterface = $this->fillByArr(new DeviceInterface(), $e);
            $devInterface->setDevice($device);
            $resp[] = $devInterface;
        }
        return $resp;
    }

}