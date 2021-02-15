<?php


namespace PonHelper\Controllers;


use DI\Annotation\Inject;
use Monolog\Logger;
use PonHelper\Models\Devices\Device;
use PonHelper\Models\SwitcherCoreAction;
use PonHelper\Models\SystemAction;
use PonHelper\Models\User\User;
use PonHelper\Storage\SwitcherCoreActionStorage;
use PonHelper\Storage\SystemActionsStorage;
use SwitcherCore\Switcher\CoreConnector;
use SwitcherCore\Switcher\Objects\TelnetLazyConnect;
use SwitcherCore\Switcher\PhpCache;

class SwitcherCore
{

    /**
     * @Inject
     * @var CoreConnector
     */
    protected $switcherCore;

    /**
     * @Inject
     * @var SwitcherCoreActionStorage
     */
    protected $actionStorage;


    /**
     * @Inject
     * @var SystemActionsStorage
     */
    protected $systemActionStorage;

    /**
     * @Inject
     * @var Logger
     */
    protected $logger;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Device
     */
    protected $device;


    function getCore(Device $dev)
    {
        $params = $dev->getModel()->getParams();
        $device = (new \SwitcherCore\Switcher\Device())
            ->setIp($dev->getIp())
            ->setCommunity($dev->getAccess()->getCommunity())
            ->setLogin($dev->getAccess()->getLogin())
            ->setPassword($dev->getAccess()->getPassword());
        if (isset($params['telnet_port'])) {
            $device->telnetPort = $params['telnet_port']['value'];
        }
        if (isset($params['mikrotik_api_port'])) {
            $device->mikrotikApiPort = $params['mikrotik_api_port']['value'];
        }
        if (isset($params['snmp_timeout'])) {
            $device->snmpTimeoutSec = $params['snmp_timeout']['value'];
        }
        if (isset($params['snmp_timeout'])) {
            $device->snmpRepeats = $params['snmp_repeats']['value'];
        }
        if (isset($params['telnet_timeout'])) {
            $device->telnetTimeout = $params['telnet_timeout']['value'];
        }
        $this->switcherCore->setCache(
            new PhpCache()
        );
        $this->switcherCore->setLogger($this->logger->withName('switcher-core'));
        $this->logger->info("Initializing core for device");
        return $this->switcherCore->getOrInit($device);
    }

    function setUser(User $user)
    {
        $this->logger->info("Setted user", $user->getAsArray());
        $this->user = $user;
        return clone $this;
    }

    function setDevice(Device $device)
    {
        $this->logger->info("Setted device", $device->getAsArray());
        $this->device = $device;
        return clone $this;
    }

    function fromDevice($module, $arguments = [], $saveToStorage = true)
    {
        $core = $this->getCore($this->device);
        try {
            $result = $core->action($module, $arguments);
            $store = null;
            if ($saveToStorage) {
                $str = $this->storeSuccess($module, $arguments, $result);
                $store = [
                    'hash' => $str->getHash(),
                    'status' => $str->getStatus(),
                    'id' => $str->getId(),
                    'time' => $str->getTime(),
                ];
            }
            try {
                $telnetOutput = $core->getContainer()->get(TelnetLazyConnect::class)->getGlobalBuffer();
            } catch (\Exception $e) {
                $this->logger->error("Error call module $module", ['arguments'=>$arguments, 'error'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()]);
                $telnetOutput = $e->getMessage();
            }
            $return = [
                'data' => $result,
                'meta' => [
                    'from_cache' => false,
                    'module' => $module,
                    'arguments' => $arguments,
                    'user' => $this->user->getAsArray(),
                    'device' => $this->device->getAsArray(),
                    'store' => $store,
                    '_telnet_output' => $telnetOutput,
                ]
            ];
            $this->storeSystemAction($module, SystemAction::STATUS_SUCCESS, $return);
            $this->logger->info("Return response - ", $return);
            return $return;
        } catch (\Exception $e) {
            try {
                $telnetOutput = $core->getContainer()->get(TelnetLazyConnect::class)->getGlobalBuffer();
            } catch (\Exception $e) {
                $this->logger->error("Error call module $module", ['arguments'=>$arguments, 'error'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()]);
                $telnetOutput = $e->getMessage();
            }
            $this->storeSystemAction($module, SystemAction::STATUS_FAILED, ['meta' => ['device' => $this->device, 'user'=>$this->user, '_telnet_output'=>$telnetOutput],'arguments'=>$arguments, 'error'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()]);
            $this->logger->error("Error call module $module", ['arguments'=>$arguments, 'error'=>$e->getMessage(), 'trace'=>$e->getTraceAsString()]);
            if ($saveToStorage) {
                $this->storeFailed($module, $arguments, 'SWITCHER_CORE_ERROR', $e->getMessage(), $e->getTrace());
            }
            throw $e;
        }
    }

    function fromCache($module, $arguments = [])
    {
        try {
            return $this->fromStore($module, $arguments);
        } catch (\Exception $e) {
            return $this->fromDevice($module, $arguments);
        }
    }

    function fromStore($module, $arguments = [])
    {
        $storage = $this->actionStorage->getLastSuccess($this->device, $module, $arguments);
        $return = [
            'data' => $storage->getData(),
            'meta' => [
                'from_cache' => true,
                'module' => $module,
                'arguments' => $arguments,
                'user' => $this->user->getAsArray(),
                'device' => $this->device->getAsArray(),
                'store' => [
                    'hash' => $storage->getHash(),
                    'status' => $storage->getStatus(),
                    'id' => $storage->getId(),
                    'time' => $storage->getTime(),
                ],
                '_telnet_output' => null,
            ]
        ];
        $this->storeSystemAction($module, SystemAction::STATUS_SUCCESS, $return);
        return $return;
    }

    protected function storeSystemAction($module, $status, $meta)
    {
        $this->systemActionStorage->add(
            (new SystemAction())
                ->setUser($this->user)
                ->setAction("module:{$module}")
                ->setMeta($meta)
                ->setStatus($status)
        );
        return $this;
    }

    protected function storeSuccess($module, $arguments, $data = [])
    {
        return $this->actionStorage->add(
            (new SwitcherCoreAction())
                ->setDevice($this->device)
                ->setUser($this->user)
                ->setArguments($arguments)
                ->setModule($module)
                ->setStatus(SwitcherCoreAction::STATUS_SUCCESS)
                ->setData($data)
        );
    }

    protected function storeFailed($module, $arguments, $errorType, $errorDescription = '', $errorStackTrace = [])
    {
        $this->actionStorage->add(
            (new SwitcherCoreAction())
                ->setDevice($this->device)
                ->setUser($this->user)
                ->setArguments($arguments)
                ->setModule($module)
                ->setStatus(SwitcherCoreAction::STATUS_FAILED)
                ->setData([
                    'type' => $errorType,
                    'description' => $errorDescription,
                    'stack_trace' => $errorStackTrace,
                ])
        );
    }
}