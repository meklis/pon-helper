<?php


namespace PonHelper\Api\Actions\Devices\Models;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Infrastructure\ImgHelper;
use PonHelper\Storage\Devices\DeviceModelStorage;
use Psr\Http\Message\ResponseInterface as Response;

class GetModelIconAction extends Action
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
        $model = $this->storage->getById($id);
        $binary = ImgHelper::getBase64Decode($model->getIcon());
        $type = ImgHelper::getMimeType($binary);
        $this->response->getBody()->write($binary);
        return $this->response->withHeader('Content-Type', $type);
    }

}