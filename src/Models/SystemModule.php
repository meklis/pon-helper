<?php


namespace PonHelper\Models;


/**
 * Class User
 * @package PonHelper\Models
 */
class SystemModule extends AbstractModel
{
    /**
     * @morm
     * @var string
     */
        protected $name;

    /**
     * @morm
     * @var string
     */
        protected $key;

    /**
     * @morm
     * @var string
     */
        protected $dir;

    /**
     * @morm
     * @var boolean
     */
        protected $enabled;

    /**
     * @prop.display=built_in
     * @morm.name=built_in
     * @var boolean
     */
     protected $builtIn;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SystemModule
     */
    public function setName(string $name): SystemModule
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return SystemModule
     */
    public function setKey(string $key): SystemModule
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     * @return SystemModule
     */
    public function setDir(string $dir): SystemModule
    {
        $this->dir = $dir;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return SystemModule
     */
    public function setEnabled(bool $enabled): SystemModule
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBuiltIn(): bool
    {
        return $this->builtIn;
    }

    /**
     * @param bool $builtIn
     * @return SystemModule
     */
    public function setBuiltIn(bool $builtIn): SystemModule
    {
        $this->builtIn = $builtIn;
        return $this;
    }



}