<?php


namespace PonHelper\Api\Actions\System;


use DI\Annotation\Inject;
use PonHelper\Api\Actions\Action;
use PonHelper\Api\DomainException\DomainRecordNotFoundException;
use PonHelper\Storage\SystemModuleStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class SystemPropertiesAction extends Action
{

    /**
     * @Inject
     * @var SystemModuleStorage
     */
    protected $moduleStorage;

    /**
     * @return Response
     */
    protected function action(): Response
    {
        return $this->respondWithData([
            'modules' => [
                'enabled' => $this->getEnabledModules(),
                'all' => array_map(function ($module) {
                    return $module->getAsArray();
                }, $this->moduleStorage->fetchAll())
            ]
        ]);
    }

    protected function getEnabledModules()
    {
        $modules = $this->moduleStorage->fetchAll();
        $keys = [];
        foreach ($modules as $module) {
            if (!$module->isEnabled()) continue;
            $keys[] = $module->getKey();
        }
        return $keys;
    }
}