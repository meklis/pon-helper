<?php


namespace PonHelper\Api\Actions\SwitcherCore;


use Monolog\Logger;
use PonHelper\Api\Actions\Action;
use PonHelper\Api\DomainException\DomainRecordNotFoundException;
use PonHelper\Controllers\SwitcherCore;
use PonHelper\Storage\Devices\DeviceStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class SwitcherCoreAction extends Action
{
    /**
     * @var SwitcherCore
     */
    protected $core;

    /**
     * @var DeviceStorage
     */
    protected $devStorage;

    function __construct(SwitcherCore $core, Logger $logger, DeviceStorage $devStorage)
    {
        $this->core = $core;
        $this->devStorage = $devStorage;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        $module = $this->request->getAttribute('module');
        $id = $this->request->getAttribute('device_id');
        $storage = $this->request->getAttribute('storage');
        try {
            $arguments = $this->getFormData();
        } catch (\Exception $e) {
            $arguments = $this->request->getQueryParams();
        }
        $core = $this->core->setUser($this->request->getAttribute('AUTH_USER'))
            ->setDevice($this->devStorage->getById($id));
        $response = null;
        $saveToStorage = !isset($arguments['store_response']) ? true : $arguments['store_response'] == 'true';
        switch ($storage) {
            case 'device': $response = $core->fromDevice($module, $arguments, $saveToStorage); break;
            case 'store': $response = $core->fromStore($module, $arguments); break;
            case 'cache': $response = $core->fromCache($module, $arguments); break;
        }
        unset($response['meta']['user']);
        unset($response['meta']['device']);
        return  $this->respondWithData(
            $response['data'],
            $response['meta']
        );
    }

}