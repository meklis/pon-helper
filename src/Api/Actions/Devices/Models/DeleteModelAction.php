<?php


namespace PonHelper\Api\Actions\Devices\Models;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Models\Devices\DeviceModel;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteModelAction extends Action
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
        $this->storage->delete(new DeviceModel($id));
        return  $this->respondWithData(true);
    }

}