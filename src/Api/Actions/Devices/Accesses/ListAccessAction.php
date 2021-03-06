<?php


namespace PonHelper\Api\Actions\Devices\Accesses;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Storage\Devices\DeviceAccessStorage;
use Psr\Http\Message\ResponseInterface as Response;

class ListAccessAction extends Action
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

        if ($id = $this->request->getAttribute('id')) {
            return $this->respondWithData(
                $this->storage->getById($id)->getAsArray()
            );
        }
        $accesses = [];
        foreach ($this->storage->fetchAll() as $access) {
            $accesses[] = $access->getAsArray();
        }
        return  $this->respondWithData($accesses);
    }
}