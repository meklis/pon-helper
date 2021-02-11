<?php


namespace PonHelper\Models\Devices;


use PonHelper\App;
use PonHelper\Models\AbstractModel;

class Device extends AbstractModel
{
    /**
     * @morm.name=ip
     * @var string
     */
    protected $ip;

    /**
     * @return mixed
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param mixed $mac
     * @return Device
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param mixed $serial
     * @return Device
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }


    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return Device
     */
    public function setIp(string $ip): Device
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Device
     */
    public function setName(string $name): Device
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Device
     */
    public function setDescription(string $description): Device
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return DeviceModel
     */
    public function getModel(): DeviceModel
    {
        return $this->model;
    }

    /**
     * @param DeviceModel $model
     * @return Device
     */
    public function setModel(DeviceModel $model): Device
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return DeviceAccess
     */
    public function getAccess(): DeviceAccess
    {
        return $this->access;
    }

    /**
     * @param DeviceAccess $access
     * @return Device
     */
    public function setAccess(DeviceAccess $access): Device
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return Device
     */
    public function setParams(?array $params): Device
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     * @return Device
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return false|string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param false|string $created_at
     * @return Device
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @morm.name=name
     * @var string
     */
    protected $name;

    /**
     * @morm.name=description
     * @var string
     */
    protected $description;

    /**
     * @var DeviceModel
     */
    protected $model;

    /**
     * @morm.name=model_id
     * @prop.display=no
     * @var int
     */
    protected $model_id;

    /**
     * @var DeviceAccess
     */
    protected $access;

    /**
     * @prop.display=no
     * @morm.name=access_id
     * @var int
     */
    protected $access_id;

    /**
     * @morm.name=params
     * @var array
     */
    protected $params;

    /**
     * @morm.name=updated_at
     * @var string
     */
    protected $updated_at;

    /**
     * @morm.name=created_at
     * @var false|string
     */
    protected $created_at;


    /**
     * @morm
     * @var
     */
    protected $mac;

    /**
     * @morm
     * @var
     */
    protected $serial;

    function __construct($id = null)
    {
        $this->created_at = date("Y-m-d H:i:s");
        $this->updated_at = date("Y-m-d H:i:s");
        $this->params = App::getInstance()->conf('devices.device_params');
        parent::__construct($id);
    }
}