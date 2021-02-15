<?php


namespace PonHelper\Controllers\Walkers;


use DI\Annotation\Inject;
use Monolog\Logger;
use PonHelper\Controllers\ActionLogger;
use PonHelper\DeviceCore\CoreInit;
use PonHelper\Models\Devices\Device;
use PonHelper\Models\Devices\DeviceInterface;
use PonHelper\Models\SystemAction;
use PonHelper\Storage\Devices\DeviceInterfaceStorage;
use PonHelper\Storage\Devices\DeviceStorage;
use SwitcherCore\Switcher\Core;
use SwitcherCore\Switcher\Objects\TelnetLazyConnect;

class UpdateInterfacesInfo
{
    /**
     * @Inject
     * @var CoreInit
     */
    protected $coreInit;

    /**
     * @Inject
     * @var ActionLogger
     */
    protected $actionLogger;

    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $deviceStorage;
    /**
     * @Inject
     * @var DeviceInterfaceStorage
     */
    protected $storage;

    /**
     * @Inject
     * @var Logger
     */
    protected $logger;

    /**
     * @var Device
     */
    protected $device;

    /**
     * @var Core
     */
    protected $core;

    function getInstance(Device $device) {
        $object = clone $this;
        $object->device = $device;
        $object->core = $this->coreInit->getCore($device);
        return $object;
    }

    function getRootInterfaces() {
        $interfaces = [];
        $model = $this->core->getDeviceMetaData()['name'];
        switch ($model) {
            case 'ZTE ZXPON C320':
                $ifaces = $this->core->action('zte_interfaces');
                foreach ($ifaces as $if) {
                    $interfaces[] = [
                        'name' => $if['interface'],
                        'type' => DeviceInterface::TYPE_PON,
                        'id' => $if['_id'],
                        'meta' => $if,
                        'parent' => null,
                        'status' => 'UNKNOWN',
                    ];
                }
                break;
            default:
                throw new \Exception("Current interface updater not support device $model");
        }
        return $interfaces;
    }

    function getAllInterfaces() {
        $rootInterfaces = $this->getRootInterfaces();
        $model = $this->core->getDeviceMetaData()['name'];
        $interfaces = [];
        foreach ($rootInterfaces as $rootInterface) {
            switch ($this->core->getDeviceMetaData()['name']) {
                case 'ZTE ZXPON C320':
                    $ifaces = $this->core->action('zte_onu_state_by_interface', ['interface' => $rootInterface['interface']]);
                    foreach ($ifaces as $if) {
                        $interfaces[] = [
                            'name' => $if['interface'],
                            'type' => DeviceInterface::TYPE_PON,
                            'id' => $if['_id'],
                            'meta' => $if,
                            'parent' => null,
                            'status' => isset($if['']),
                        ];
                    }
                    break;
                default:
                    throw new \Exception("Current interface updater not support device $model");
            }
        }

        return array_merge($interfaces, $rootInterfaces);
    }

}