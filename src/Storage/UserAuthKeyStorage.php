<?php


namespace PonHelper\Storage;


use DI\Annotation\Inject;
use PonHelper\Models\User\User;
use PonHelper\Models\User\UserAuthKey;

class UserAuthKeyStorage extends AbstractStorage
{
    protected $tableName = 'user_auth_keys';

    /**
     * @Inject
     * @var UserStorage
     */
    protected $userStorage;


    function fill($object)
    {
        $fillabled = parent::fill($object);
        if(!$fillabled) return null;
        $fillabled->user = $this->userStorage->fill((new User())->setId($fillabled->user_id));
        return $fillabled;
    }

    /**
     * @param string $key
     * @return UserAuthKey|null
     */
    function findByKey(string $key) {
        $psth = $this->pdo->prepare("SELECT id FROM user_auth_keys WHERE `key` = ?");
        $psth->execute([$key]);
        if($psth->rowCount() === 0) {
            return null;
        }
        return  $this->fill((new UserAuthKey())->setId($psth->fetch()['id']));
    }
    function add($object)
    {
        $object->user_id = $object->user->id;
        return parent::add($object); // TODO: Change the autogenerated stub
    }
    function update($object)
    {
        $object->user_id = $object->user->id;
        return parent::update($object); // TODO: Change the autogenerated stub
    }
    function findLastByUser(User $user) {
        $id = $this->getOneIdByWhere("user_id = ? order by id desc limit 1", [$user->getId()]);
        if(!$id) {
            return null;
        }
        return  $this->fill(
            (new UserAuthKey())->setId($id)
        );
    }

}