<?php


namespace PonHelper\Models;


use PonHelper\Models\User\User;

class SystemAction extends AbstractModel
{

    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILED = 'FAILED';


    function __construct($id = null)
    {
        $this->createdAt = date("Y-m-d H:i:s");
        $this->action = 'UNKNOWN';
        $this->status = 'SUCCESS';
        parent::__construct($id);
    }

    /**
     * @morm.name=created_at
     * @prop.name=created_at
     * @var string
     */
    protected $createdAt;

    /**
     * @morm
     * @var string
     */
    protected $action;

    /**
     * @morm
     * @prop.display=no
     * @var int
     */
    protected $user_id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @morm
     * @var string
     */
    protected $message;

    /**
     * @morm
     * @var array
     */
    protected $meta;

    /**
     * @morm
     * @var string
     */
    protected $status;

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     * @return SystemAction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return SystemAction
     */
    public function setAction(string $action): SystemAction
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return SystemAction
     */
    public function setUser(User $user): SystemAction
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return SystemAction
     */
    public function setMessage(string $message): SystemAction
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     * @return SystemAction
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return SystemAction
     */
    public function setStatus(string $status): SystemAction
    {
        $this->status = $status;
        return $this;
    }




}