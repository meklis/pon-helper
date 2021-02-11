<?php
declare(strict_types=1);

namespace PonHelper\Api\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class SetUserStatusAction extends UserAction
{

    protected function action(): Response
    {
       $employeeId = $this->request->getQueryParams()['USER_ID'];
       $status = isset($this->getFormData()['status']) ? $this->getFormData()['status'] : '';
       if(!$status) {
           throw new HttpBadRequestException($this->request, "Field status is required");
       }
       return $this->respondWithData($this->user->setUserStatus($employeeId, $status)->getUserStatus($employeeId));
    }
}
