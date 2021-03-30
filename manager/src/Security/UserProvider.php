<?php

namespace App\Security;

use App\ModelReader\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserFetcher
     */
    private $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->getUserIdentify($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserAuth) {
            throw new UnsupportedUserException('Invalid user class ' . \get_class($user));
        }

        return $this->getUserIdentify($user->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserAuth::class;
    }

    private function getUserIdentify(string $username): UserInterface
    {
        $user = $this->users->fetchForAuth($username);

        if (!$user) {
            throw new UsernameNotFoundException('');
        }

        return new UserAuth(
            $user['id'],
            $user['email'],
            $user['login'],
            $user['password'],
            $user['role'],
            $user['status']
        );
    }
}