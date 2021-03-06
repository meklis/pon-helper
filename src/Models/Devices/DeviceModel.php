<?php


namespace PonHelper\Models\Devices;


use PonHelper\App;
use PonHelper\Models\AbstractModel;

class DeviceModel extends AbstractModel
{
    /**
     * @morm
     * @var string
     */
    protected $name;

    /**
     * @morm
     * @var array
     */
    protected $params;

    /**
     * @morm
     * @var string
     */
    protected $vendor;

    /**
     * @morm
     * @var string
     */
    protected $model;

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return DeviceModel
     */
    public function setIcon(string $icon): DeviceModel
    {
        $this->icon = $icon;
        return $this;
    }


    /**
     * @morm
     * @var string
     */
    protected $icon;

    /**
     * DeviceModel constructor.
     * @param null $id
     */

    function __construct($id = null) {
        parent::__construct($id);
        $this->params = App::getInstance()->conf('devices.model_params');
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
     * @return DeviceModel
     */
    public function setName(string $name): DeviceModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return DeviceModel
     */
    public function setParams(array $params): DeviceModel
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return string
     */
    public function getVendor(): string
    {
        return $this->vendor;
    }

    /**
     * @param string $vendor
     * @return DeviceModel
     */
    public function setVendor(string $vendor): DeviceModel
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return DeviceModel
     */
    public function setModel(string $model): DeviceModel
    {
        $this->model = $model;
        return $this;
    }

    function __toString()
    {
        return $this->name; // TODO: Change the autogenerated stub
    }

}