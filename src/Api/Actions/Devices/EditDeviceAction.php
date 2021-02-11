<?php


namespace PonHelper\Api\Actions\Devices;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use PonHelper\Storage\Devices\DeviceModelStorage;
use PonHelper\Storage\Devices\DeviceStorage;
use Psr\Http\Message\ResponseInterface as Response;

class EditDeviceAction extends Action
{
    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $storage;

    /**
     * @Inject
     * @var DeviceAccessStorage
     */
    protected $accessStorage;

    /**
     * @Inject
     * @var DeviceModelStorage
     */
    protected $modelStorage;

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $device = $this->storage->getById($id);
        $data = $this->getFormData();
        if(isset($data['name'])) {
            $device->setName($data['name']);
        }
        if(isset($data['ip'])) {
            $device->setIp($data['ip']);
        }
        if(isset($data['description'])) {
            $device->setDescription($data['description']);
        }
        if(isset($data['access']['id'])) {
            $device->setAccess($this->accessStorage->getById($data['access']['id']));
        }
        if(isset($data['model']['id'])) {
            $device->setModel($this->modelStorage->getById($data['model']['id']));
        }
        if(isset($data['mac'])) {
            $device->setMac($data['mac']);
        }
        if(isset($data['serial'])) {
            $device->setSerial($data['serial']);
        }
        if(isset($data['parameters'])) {
            $device->setParams($data['parameters']);
        }
        $access = $this->storage->update($device);
        return  $this->respondWithData($access->getAsArray());
    }

}