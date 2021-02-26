<?php


namespace PonHelper\Models\User;


use PonHelper\Models\AbstractModel;

/**
 * Class User
 * @package PonHelper\Models
 */
class User extends AbstractModel
{

    /**
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    function __construct($id = null)
    {
        parent::__construct($id);
        $this->created_at = date("Y-m-d H:i:s");
        $this->updated_at = date("Y-m-d H:i:s");
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return UserGroup
     */
    public function getGroup(): UserGroup
    {
        return $this->group;
    }

    /**
     * @param UserGroup $group
     * @return User
     */
    public function setGroup(UserGroup $group): User
    {
        $this->group = $group;
        $this->group_id = $group->getId();
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return User
     */
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return User
     */
    public function setCreatedAt(string $created_at): User
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     * @return User
     */
    public function setUpdatedAt(string $updated_at): User
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    /**
     * @morm.name=id
     * @var int
     */
   protected $id;
    /**
     * @morm.name=name
     * @var string
     */
   protected $name;

    /**
     * @var UserGroup
     */
   protected $group;

    /**
     * @prop.display=no
     * @morm.name=group_id
     * @var int
     */
   protected $group_id;

    /**
     * @morm.name=password
     * @prop.display=no
     * @var string
     */
   protected $password;

    /**
     * @morm.name=created_at
     * @var string
     */
   protected $created_at;

    /**
     * @morm.name=updated_at
     * @var string
     */
   protected $updated_at;

    /**
     * @var string
     * @morm.name=login
     */
   protected $login;

    /**
     * @var array
     */
   protected $last_activity;

}