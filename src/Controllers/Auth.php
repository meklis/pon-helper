<?php


namespace PonHelper\Controllers;


use DateTime;
use Exception;
use PonHelper\App;
use PonHelper\Models\User\User;
use PonHelper\Models\User\UserAuthKey;
use PonHelper\Storage\UserAuthKeyStorage;
use PonHelper\Storage\UserStorage;

class Auth
{
    protected $app;
    protected $keyStorage;
    protected $userStorage;
    function __construct(App $app, UserAuthKeyStorage $keyStorage, UserStorage $userStorage) {
        $this->app =  $app;
        $this->keyStorage = $keyStorage;
        $this->userStorage = $userStorage;
    }
    function isKeyValid(string $key) {
        $key = $this->keyStorage->findByKey($key);
        if(!$key) {
            throw new \Exception("Auth key is invalid");
        }
        $expiredTimeStamp = DateTime::createFromFormat("Y-m-d H:i:s", $key->getExpiredAt())->getTimestamp();
        return time() < $expiredTimeStamp && $key->getStatus() == UserAuthKey::STATUS_ACTIVE;
    }
    function updateLastActivity(string $key) {
        $key = $this->keyStorage->findByKey($key);
        if(!$key) {
            throw new \Exception("Auth key is invalid");
        }
        $key->setLastActivity(date("Y-m-d H:i:s"));
        $this->keyStorage->update($key);
        return $this;
    }
    function getUserByKey(string  $key) {
        $key = $this->keyStorage->findByKey($key);
        return $key->getUser();
    }
    function checkPair(string $login, string $password) {
        $user = $this->userStorage->getUserByLogin($login);
        if(!$user) {
            throw new Exception("User with login $login not found");
        }
        if($user->getPassword() === sha1($password)) {
            return $user;
        }
        throw new Exception("User or password is incorrect");
    }

    /**
     * @param User $user
     * @return UserAuthKey
     */
    function generateKey(User $user, $expiredSec = null) {
        $userKey = (new UserAuthKey())->setUser($user);
        if($expiredSec) {
            $userKey->setExpiredAt(
                date("Y-m-d H:i:s",$expiredSec + time())
            );
        } else {
            $userKey->setExpiredAt(
                date("Y-m-d H:i:s", $this->app->conf('api.auth.key_expired_sec') + time())
            );
        }
        $userKey->setStatus(UserAuthKey::STATUS_ACTIVE);
        return $this->keyStorage->add($userKey);
    }
    /**
     * @param User $user
     * @return UserAuthKey
     */
    function getLastLogin(User $user) {
        $userKey = (new UserAuthKey())->setExpiredAt(
            date("Y-m-d H:i:s", $this->app->conf('api.auth.key_expired_sec') + time())
        )->setUser($user);
        return $this->keyStorage->add($userKey);
    }
}