<?php


namespace PonHelper\Api\Actions\Devices\Models;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Models\Devices\DeviceModel;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddModelAction extends Action
{
    /**
     * @Inject
     * @var DeviceModelStorage
     */
    protected $storage;

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $Model = new DeviceModel();
        $data = $this->getFormData();

        if(isset($data['vendor'])) {
            $Model->setVendor($data['vendor']);
        } else {
            throw new HttpBadRequestException($this->request, "Vendor is required");
        }
        if(isset($data['model'])) {
            $Model->setModel($data['model']);
        } else {
            throw new HttpBadRequestException($this->request, "Model is required");
        }
        if(isset($data['name'])) {
            $Model->setName($data['name']);
        } else {
            $Model->setName("{$data['vendor']} {$data['model']}");
        }
        if(isset($data['params'])) {
            $Model->setParams($data['params']);
        }
        $Model = $this->storage->add($Model);
        return  $this->respondWithData($Model->getAsArray());
    }

}