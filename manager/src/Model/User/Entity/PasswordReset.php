<?php

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_password_reset")
 */
class PasswordReset
{
    public const INTERVAL = 'PT5M';
    public const EXPIRE_TIME = 'P1D';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=40)
     */
    private $token;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    public function __construct(User $user, string $password, string $token, \DateTimeImmutable $date)
    {
        $this->user = $user;
        $this->password = $password;
        $this->token = $token;
        $this->date = $date;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function resetTimeoutIsOut(\DateTimeImmutable $currentTime): void
    {
        if($currentTime < $this->date->add(new \DateInterval(self::INTERVAL))) {
            throw new \DomainException('You can reset your password only once every 5 minutes.');
        }
    }

    public function tokenIsNotExpired(\DateTimeImmutable $currentTime): void
    {
        if($currentTime > $this->date->add(new \DateInterval(self::EXPIRE_TIME))) {
            throw new \DomainException('Password reset token is expired.');
        }
    }
}