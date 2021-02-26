<?php


namespace PonHelper\Api\Actions\Auth;


use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use DI\Annotation\Inject;
use Exception;
use PonHelper\Api\Actions\Action;
use PonHelper\Controllers\Auth;
use PonHelper\Storage\UserAuthKeyStorage;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;

class UserAuthAction extends Action
{
    /**
     * @Inject
     * @var Auth
     */
    protected $auth;

    /**
     * @Inject
     * @var UserAuthKeyStorage
     */
    protected $keyStorage;

    /**
     * @return Response
     * @throws HttpBadRequestException
     * @throws HttpUnauthorizedException
     */

    protected function action(): Response
    {
        $data = $this->getFormData();
        if (!isset($data['login']) || !isset($data['password'])) {
            throw new HttpBadRequestException($this->request, "Login and password are required fields");
        }
        $user = null;
        try {
            $user = $this->auth->checkPair($data['login'], $data['password']);
        } catch (Exception $e) {
            throw new HttpUnauthorizedException($this->request, $e->getMessage());
        }

        $key = $this->auth->generateKey($user);
        $key->setUserAgent($this->request->getHeaderLine('User-Agent'));
        $key->setRemoteAddr($this->request->getServerParams()['REMOTE_ADDR']);

        AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);
        $dd = new DeviceDetector($this->request->getHeaderLine('User-Agent'));
        $dd->parse();

        if ($dd->isBot()) {
            $key->setDeviceInfo([
                'bot' => $dd->getBot(),
                'client' => null,
                'os_info' => null,
                'device' => null,
                'brand' => null,
                'model' => null,
            ]);
        } else {
            $key->setDeviceInfo([
                'bot' => null,
                'client' => $dd->getClient(),
                'os_info' => $dd->getOs(),
                'device' => $dd->getDeviceName(),
                'brand' => $dd->getBrandName(),
                'model' => $dd->getModel(),
            ]);
        }

        $this->keyStorage->update($key);

        return $this->respondWithData($key->getAsArray());
    }

}