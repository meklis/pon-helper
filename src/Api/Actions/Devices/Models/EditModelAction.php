<?php


namespace PonHelper\Api\Actions\Devices\Models;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Psr\Http\Message\ResponseInterface as Response;

class EditModelAction extends Action
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
        $id = $this->request->getAttribute('id');
        $Model = $this->storage->getById($id);
        $data = $this->getFormData();
        if(isset($data['name'])) {
            $Model->setName($data['name']);
        }
        if(isset($data['vendor'])) {
            $Model->setVendor($data['vendor']);
        }
        if(isset($data['model'])) {
            $Model->setModel($data['model']);
        }
        if(isset($data['params'])) {
            $Model->setParams($data['params']);
        }
        $Model = $this->storage->update($Model);
        return  $this->respondWithData($Model->getAsArray());
    }

}