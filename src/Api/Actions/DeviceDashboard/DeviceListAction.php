<?php


namespace PonHelper\Api\Actions\DeviceDashboard;


use Monolog\Logger;
use PonHelper\Api\Actions\Action;
use PonHelper\Api\DomainException\DomainRecordNotFoundException;
use PonHelper\Controllers\SwitcherCore;
use PonHelper\Models\Devices\Device;
use PonHelper\Storage\Devices\DeviceStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeviceListAction extends Action
{

    /**
     * @var SwitcherCore
     */
    protected $switcherCore;

    /**
     * @var DeviceStorage
     */
    protected $storage;


    function __construct(DeviceStorage $storage, SwitcherCore $switcherCore, Logger $logger)
    {
        $this->switcherCore = $switcherCore;
        $this->storage = $storage;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $devices = [];
        foreach ($this->storage->fetchAll() as $device) {
            $dev = $device->getAsArray();
            $dev['interfaces'] = $this->getInterfacesStat($device);
            $devices[] = $dev;
        }
        return $this->respondWithData($devices);
    }

    protected function getInterfacesStat(Device $device) {
        $core = $this->switcherCore->setDevice($device)->setUser($this->request->getAttribute('AUTH_USER'));
        $response = [
            'count' => 0,
            'online' => 0,
            'offline' => 0,
            'up' => 0,
            'down' => 0,
            'last_check' => null,
        ];
        $interfaceInformation = $core->fromStore('interfaces_status');
        $response['last_check'] = $interfaceInformation['meta']['store']['time'];
        foreach ($interfaceInformation['data'] as $interface) {
            if($interface['status'] == 'Online') {
                $response['online']++;
                $response['count']++;
            }
            if($interface['status'] == 'Offline') {
                $response['count']++;
                $response['offline']++;
            }
            if($interface['status'] == 'Up') {
                $response['count']++;
                $response['up']++;
            }
            if($interface['status'] == 'Down') {
                $response['count']++;
                $response['down']++;
            }

        }
        return $response;
    }

}