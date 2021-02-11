<?php


namespace PonHelper\Api\Actions\Devices;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Models\Devices\Device;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use PonHelper\Storage\Devices\DeviceModelStorage;
use PonHelper\Storage\Devices\DeviceStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddDeviceAction extends Action
{
    /**
     * @Inject
     * @var DeviceStorage
     */
    protected $storage;

    /**
     * @Inject
     * @var DeviceModelStorage
     */
    protected $modelStorage;
    /**
     * @Inject
     * @var DeviceAccessStorage
     */
    protected $accessStorage;

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $device = new Device();
        $data = $this->getFormData();
        if(isset($data['name'])) {
            $device->setName($data['name']);
        } else {
            throw new HttpBadRequestException($this->request, "name is required");
        }
        if(isset($data['ip'])) {
            $device->setIp($data['ip']);
        } else {
            throw new HttpBadRequestException($this->request, "ip is required");
        }
        if(isset($data['description'])) {
            $device->setDescription($data['description']);
        }
        if(isset($data['access']['id'])) {
            $device->setAccess($this->accessStorage->getById($data['access']['id']));
        } else {
            throw new HttpBadRequestException($this->request, "access is required");
        }
        if(isset($data['model']['id'])) {
            $device->setModel($this->modelStorage->getById($data['model']['id']));
        } else {
            throw new HttpBadRequestException($this->request, "model is required");
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
        
        $model = $this->storage->add($device);
        return  $this->respondWithData($model->getAsArray());
    }

}