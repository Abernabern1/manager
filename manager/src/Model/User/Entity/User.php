<?php

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 * })
 */
class User
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_ACTIVE = 'active';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Email
     * @ORM\Column(type="user_email")
     */
    private $email;
    /**
     * @var Login
     * @ORM\Column(type="user_login")
     */
    private $login;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $status;
    /**
     * @var Role
     * @ORM\Column(type="user_role")
     */
    private $role;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmToken;

    public function __construct(Email $email, Login $login, string $password, string $confirmToken)
    {
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->confirmToken = $confirmToken;

        $this->status = self::STATUS_WAITING;
        $this->role = new Role(Role::USER);
    }

    public function activate(): void
    {
        if($this->status !== self::STATUS_WAITING) {
            throw new \DomainException('User is not waiting for activation.');
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function changePassword(string $password): void
    {
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function statusIsWaiting(): bool
    {
        if($this->status === self::STATUS_WAITING) {
            return true;
        }

        return false;
    }

    public function statusIsActive(): bool
    {
        if($this->status === self::STATUS_ACTIVE) {
            return true;
        }

        return false;
    }

    public function roleIsUser(): bool
    {
        return $this->role->isUser();
    }
}