<?php
namespace PonHelper\Models\User;
use PonHelper\Models\AbstractModel;
use Ramsey\Uuid\Uuid;


class UserAuthKey extends AbstractModel
{

    /**
     * @prop.display=no
     * @morm.name=user_id
     * @var int
     */
    protected $user_id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @morm.name=created_at
     * @var string
     */
    protected $created_at;

    /**
     * @morm.name=expired_at
     * @var string
     */
    protected $expired_at;

    /**
     * @morm.name=key
     * @var string
     */
    protected $key;

    /**
     * @morm.name=user_agent
     * @prop.name=user_agent
     * @var
     */
    protected $userAgent;


    /**
     * @morm.name=remote_addr
     * @prop.name=remote_addr
     * @var
     */
    protected $remoteAddr;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return UserAuthKey
     */
    public function setUserId(int $user_id): UserAuthKey
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param mixed $userAgent
     * @return UserAuthKey
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemoteAddr()
    {
        return $this->remoteAddr;
    }

    /**
     * @param mixed $remoteAddr
     * @return UserAuthKey
     */
    public function setRemoteAddr($remoteAddr)
    {
        $this->remoteAddr = $remoteAddr;
        return $this;
    }


    function __construct($id = null)
    {
        parent::__construct($id);
        $this->created_at = date("Y-m-d H:i:s");
        $this->updated_at = date("Y-m-d H:i:s");
        $this->key = $this->generateKey();
    }
    function generateKey() {
        return Uuid::getFactory()->uuid4()->toString();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserAuthKey
     */
    public function setId($id): UserAuthKey
    {
        $this->id = $id;
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
     * @return UserAuthKey
     */
    public function setUser(User $user): UserAuthKey
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return UserAuthKey
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiredAt(): string
    {
        return $this->expired_at;
    }

    /**
     * @param string $expired_at
     * @return UserAuthKey
     */
    public function setExpiredAt(string $expired_at): UserAuthKey
    {
        $this->expired_at = $expired_at;
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
     * @return UserAuthKey
     */
    public function setKey(string $key): UserAuthKey
    {
        $this->key = $key;
        return $this;
    }

}