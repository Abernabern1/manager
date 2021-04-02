<?php

namespace App\Security;

use App\Model\User\Entity\User;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAuth implements UserInterface, EquatableInterface
{
    /**
     * @var FlashBagInterface
     */
    private $session;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $role;
    /**
     * @var string
     */
    private $status;

    public function __construct(
        FlashBagInterface $session,
        string $id,
        string $email,
        string $login,
        string $password,
        string $role,
        string $status
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
        $this->session = $session;
    }

    public function getUsername(): string
    {
        return $this->getLogin();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {

    }

    public function isActive(): bool
    {
        return $this->status === User::STATUS_ACTIVE;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id &&
            $this->email === $user->email &&
            $this->login === $user->login &&
            ($this->passwordIsChanged() || $this->password === $user->password) &&
            $this->role === $user->role &&
            $this->status === $user->status;
    }

    private function passwordIsChanged(): bool
    {
        return in_array('Password is successfully changed.', $this->session->peek('success'));
    }
}