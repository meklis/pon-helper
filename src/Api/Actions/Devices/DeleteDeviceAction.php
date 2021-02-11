<?php


namespace PonHelper\Api\Actions\Devices;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Models\Devices\DeviceAccess;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteDeviceAction extends Action
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
        $this->storage->delete(new DeviceAccess($id));
        return  $this->respondWithData(true);
    }

}