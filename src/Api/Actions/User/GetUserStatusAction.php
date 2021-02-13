<?php
declare(strict_types=1);

namespace PonHelper\Api\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class GetUserStatusAction extends UserAction
{

    protected function action(): Response
    {
       $employeeId = $this->request->getQueryParams()['USER_ID'];
       return $this->respondWithData($this->user->getUserStatus($employeeId));
    }
}
