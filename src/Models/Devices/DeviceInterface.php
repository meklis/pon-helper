<?php


namespace PonHelper\Models\Devices;


use PonHelper\App;
use PonHelper\Models\AbstractModel;

class DeviceInterface extends AbstractModel
{

    const TYPE_ETHER = 'ETHERNET';
    const TYPE_SFP = 'SFP';
    const TYPE_PON = 'PON';
    const TYPE_ONU = 'ONU';
    const TYPE_UNI = 'UNI';
    const STATUS_ONLINE = 'ONLINE';
    const STATUS_OFFLINE = 'OFFLINE';
    const STATUS_DISABLED = 'DISABLED';
    const STATUS_UNKNOWN = 'UNKNOWN';

    function __construct($id = null)
    {
        $this->updated_at = date("Y-m-d H:i:s");
        parent::__construct($id);
    }



    /**
     * @return DeviceInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param DeviceInterface[] $children
     * @return DeviceInterface
     */
    public function setChildren(array $children): DeviceInterface
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @var DeviceInterface[]
     */
     protected $children;

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     * @return DeviceInterface
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return DeviceInterface
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @param mixed $device
     * @return DeviceInterface
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return DeviceInterface
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailed()
    {
        return $this->detailed;
    }

    /**
     * @param mixed $detailed
     * @return DeviceInterface
     */
    public function setDetailed($detailed)
    {
        $this->detailed = $detailed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return DeviceInterface
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return DeviceInterface
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }



}