<?php


namespace PonHelper\DeviceCore;


use DI\Annotation\Inject;
use Monolog\Logger;
use PonHelper\App;
use PonHelper\Models\Devices\Device;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use SwitcherCore\Switcher\Core;
use SwitcherCore\Switcher\CoreConnector;
use SwitcherCore\Switcher\PhpCache;

class CoreInit
{

    /**
     * @Inject
     * @var App
     */
    protected $app;

    /**
     * @Inject
     * @var DeviceAccessStorage
     */
    protected $device;

    /**
     * @Inject
     * @var CoreConnector
     */
    protected $switcherCore;

    /**
     * @Inject
     * @var Logger
     */
    protected $logger;

    /**
     *
     * @var Core
     */
    protected $core;

    function getCore(Device $dev) {
        $params = $dev->getModel()->getParams();
        $device = (new \SwitcherCore\Switcher\Device())
            ->setIp($dev->getIp())
            ->setCommunity($dev->getAccess()->getCommunity())
            ->setLogin($dev->getAccess()->getLogin())
            ->setPassword($dev->getAccess()->getPassword());
        if(isset($params['telnet_port'])) {
            $device->telnetPort = $params['telnet_port'];
        }
        if(isset($params['mikrotik_api_port'])) {
            $device->mikrotikApiPort = $params['mikrotik_api_port']['value'];
        }
        if(isset($params['snmp_timeout'])) {
            $device->snmpTimeoutSec = $params['snmp_timeout']['value'];
        }
        if(isset($params['snmp_timeout'])) {
            $device->snmpRepeats = $params['snmp_repeats']['value'];
        }
        if(isset($params['telnet_timeout'])) {
            $device->telnetTimeout = $params['telnet_timeout']['value'];
        }
        $this->switcherCore->setCache(
            new PhpCache()
        );
        $this->switcherCore->setLogger($this->logger->withName('switcher-core'));
        return  $this->switcherCore->init($device);
    }

}