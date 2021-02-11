<?php


namespace PonHelper\Api\Actions\Devices\Models;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Psr\Http\Message\ResponseInterface as Response;

class ListModelAction extends Action
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

        if ($id = $this->request->getAttribute('id')) {
            return $this->respondWithData(
                $this->storage->getById($id)->getAsArray()
            );
        }
        $Models = [];
        foreach ($this->storage->fetchAll() as $Model) {
            $Models[] = $Model->getAsArray();
        }
        return  $this->respondWithData($Models);
    }

}