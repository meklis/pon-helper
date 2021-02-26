<?php
declare(strict_types=1);

use PonHelper\Api\Actions\Auth\UserAuthAction;
use PonHelper\Api\Actions\Devices\Accesses\AddAccessAction;
use PonHelper\Api\Actions\Devices\Accesses\DeleteAccessAction;
use PonHelper\Api\Actions\Devices\Accesses\EditAccessAction;
use PonHelper\Api\Actions\Devices\Accesses\ListAccessAction;
use PonHelper\Api\Actions\Devices\AddDeviceAction;
use PonHelper\Api\Actions\Devices\DeleteDeviceAction;
use PonHelper\Api\Actions\Devices\EditDeviceAction;
use PonHelper\Api\Actions\Devices\ListDeviceAction;
use PonHelper\Api\Actions\Devices\Models\AddModelAction;
use PonHelper\Api\Actions\Devices\Models\DeleteModelAction;
use PonHelper\Api\Actions\Devices\Models\EditModelAction;
use PonHelper\Api\Actions\Devices\Models\ListModelAction;
use PonHelper\Api\Actions\User\AddUserAction;
use PonHelper\Api\Actions\User\DeleteUserAction;
use PonHelper\Api\Actions\User\GetAllUsersAction;
use PonHelper\Api\Actions\User\GetUserAction;
use PonHelper\Api\Actions\User\UpdateUserAction;
use PonHelper\Api\Actions\UserGroup\AddGroupUserAction;
use PonHelper\Api\Actions\UserGroup\DeleteGroupUserAction;
use PonHelper\Api\Actions\UserGroup\GetAllUserGroupsAction;
use PonHelper\Api\Actions\UserGroup\GetGroupUserAction;
use PonHelper\Api\Actions\UserGroup\PermissionListAction;
use PonHelper\Api\Actions\UserGroup\UpdateGroupUserAction;
use PonHelper\Api\Middleware\AuthKeyMiddleware;
use PonHelper\Api\Middleware\PermissionCheckMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/v1', function (Group $group) {
        $group->get('/sys/properties', \PonHelper\Api\Actions\System\SystemPropertiesAction::class);
        $group->post('/auth', UserAuthAction::class);
        $group->group('', function (Group $group) {
            $group->get('/device-dashboard', \PonHelper\Api\Actions\DeviceDashboard\DeviceListAction::class);
            $group->group('/user', function (Group $group) {
                $group->get('', GetAllUsersAction::class);
                $group->get('/{id}', GetUserAction::class);
                $group->post('', AddUserAction::class);
                $group->put('/{id}', UpdateUserAction::class);
                $group->delete('/{id}', DeleteUserAction::class);
            });
            $group->group('/user-group', function (Group $group) {
                $group->get('', GetAllUserGroupsAction::class);
                $group->get('/{id}', GetGroupUserAction::class);
                $group->post('', AddGroupUserAction::class);
                $group->put('/{id}', UpdateGroupUserAction::class);
                $group->delete('/{id}', DeleteGroupUserAction::class);
            });
            $group->group('/device-access', function (Group $group) {
                $group->get('', ListAccessAction::class);
                $group->get('/{id}', ListAccessAction::class);
                $group->post('', AddAccessAction::class);
                $group->put('/{id}', EditAccessAction::class);
                $group->delete('/{id}', DeleteAccessAction::class);
            });
            $group->group('/device-model', function (Group $group) {
                $group->get('', ListModelAction::class);
                $group->get('/{id}', ListModelAction::class);
                $group->post('', AddModelAction::class);
                $group->put('/{id}', EditModelAction::class);
                $group->delete('/{id}', DeleteModelAction::class);
            });
            $group->get('/device-icon/{id}', \PonHelper\Api\Actions\Devices\Models\GetModelIconAction::class);
            $group->group('/device', function (Group $group) {
                $group->get('', ListDeviceAction::class);
                $group->get('/{id}', ListDeviceAction::class);
                $group->post('', AddDeviceAction::class);
                $group->put('/{id}', EditDeviceAction::class);
                $group->delete('/{id}', DeleteDeviceAction::class);
            });
            $group->group('/system', function (Group  $group) {
                $group->get('/permissions', PermissionListAction::class);
            });
            $group->map(['GET', 'POST'], '/switcher-core/{storage}/{module}/{device_id}', \PonHelper\Api\Actions\SwitcherCore\SwitcherCoreAction::class);
        })->add(PermissionCheckMiddleware::class)->add(AuthKeyMiddleware::class);
    });

    //System routes
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) {
        throw new HttpNotFoundException($request);
    });
};
