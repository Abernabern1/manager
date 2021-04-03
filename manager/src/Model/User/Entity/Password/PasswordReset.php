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
    public const INTERVAL = 'PT5M';
    public const EXPIRE_TIME = 'P1D';

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, length=40)
     */
    protected $token;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Model\User\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    protected $date;


    public function __construct(User $user, string $token, \DateTimeImmutable $date)
    {
        $this->token = $token;
        $this->user = $user;
        $this->date = $date;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function tokenTimeoutIsOut(\DateTimeImmutable $currentTime): void
    {
        if($currentTime < $this->date->add(new \DateInterval(self::INTERVAL))) {
            throw new \DomainException('Password reset is available only once every 5 minutes.');
        }
    }

    public function tokenIsNotExpired(\DateTimeImmutable $currentTime): void
    {
        if($currentTime > $this->date->add(new \DateInterval(self::EXPIRE_TIME))) {
            throw new \DomainException('Password reset token is expired.');
        }
    }
}