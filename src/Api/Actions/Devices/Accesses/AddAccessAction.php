<?php


namespace PonHelper\Api\Actions\Devices\Accesses;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Models\Devices\DeviceAccess;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddAccessAction extends Action
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
        $access = new DeviceAccess();
        $data = $this->getFormData();
        if(isset($data['name'])) {
            $access->setName($data['name']);
        } else {
            throw new HttpBadRequestException($this->request, "Name is required");
        }
        if(isset($data['community'])) {
            $access->setCommunity($data['community']);
        } else {
            throw new HttpBadRequestException($this->request, "Community is required");
        }
        if(isset($data['login'])) {
            $access->setLogin($data['login']);
        } else {
            throw new HttpBadRequestException($this->request, "Login is required");
        }
        if(isset($data['password'])) {
            $access->setPassword($data['password']);
        } else {
            throw new HttpBadRequestException($this->request, "Password is required");
        }
        $access = $this->storage->add($access);
        return  $this->respondWithData($access->getAsArray());
    }

}