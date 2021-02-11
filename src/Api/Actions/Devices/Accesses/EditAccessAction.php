<?php


namespace PonHelper\Api\Actions\Devices\Accesses;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use Psr\Http\Message\ResponseInterface as Response;

class EditAccessAction extends Action
{
    /**
     * @Inject
     * @var DeviceAccessStorage
     */
    protected $storage;

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $id = $this->request->getAttribute('id');
        $access = $this->storage->getById( $id);
        $data = $this->getFormData();
        if(isset($data['name'])) {
            $access->setName($data['name']);
        }
        if(isset($data['community'])) {
            $access->setCommunity($data['community']);
        }
        if(isset($data['login'])) {
            $access->setLogin($data['login']);
        }
        if(isset($data['password'])) {
            $access->setPassword($data['password']);
        }
        $access = $this->storage->update($access);
        return  $this->respondWithData($access->getAsArray());
    }

}