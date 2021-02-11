<?php

namespace PonHelper\Models\User;
use PonHelper\Models\AbstractModel;


/**
 * Class UserGroup
 * @package PonHelper\Models\User
 */
class UserGroup extends AbstractModel
{
    /**
     *
     * @morm.name=id
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserGroup
     */
    public function setId($id): UserGroup
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
     * @return UserGroup
     */
    public function setName(string $name): UserGroup
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisplay(): bool
    {
        return $this->display;
    }

    /**
     * @param bool $display
     * @return UserGroup
     */
    public function setDisplay(bool $display): UserGroup
    {
        $this->display = $display;
        return $this;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     * @return UserGroup
     */
    public function setPermissions(array $permissions): UserGroup
    {
        $this->permissions = $permissions;
        return $this;
    }
    /**
     * @morm.name=name
     * @var string;
     */
    protected $name;

    /**
     * @morm
     * @var bool
     */
    protected $display;

    /**
     * @morm
     * @var array
     */
    protected $permissions;

    function __toString()
    {
        return $this->name;
    }

}