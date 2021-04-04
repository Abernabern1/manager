<?php

namespace App\Model\User\Entity\Password;

use App\Model\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_password_resets")
 */
class PasswordReset
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=40)
     */
    private $token;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Model\User\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var PasswordDateTime
     * @ORM\Column(type="user_password_date_time", name="date_time")
     */
    private $dateTime;


    public function __construct(User $user, string $token, PasswordDateTime $dateTime)
    {
        $this->token = $token;
        $this->user = $user;
        $this->dateTime = $dateTime;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function tokenTimeoutIsOut(\DateTimeImmutable $currentDateTime): void
    {
        if(!$this->dateTime->timeoutIsOut($currentDateTime)) {
            throw new \DomainException('Password reset is available only once every 5 minutes.');
        }
    }

    public function tokenIsNotExpired(\DateTimeImmutable $currentDateTime): void
    {
        if($this->dateTime->isExpired($currentDateTime)) {
            throw new \DomainException('Password reset token is expired.');
        }
    }
}