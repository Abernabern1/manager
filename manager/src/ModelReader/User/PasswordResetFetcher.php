<?php

namespace App\ModelReader\User;

use Doctrine\DBAL\Connection;

class PasswordResetFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function existsByToken(string $token): bool
    {
        return (bool) $this->connection->createQueryBuilder()
            ->select('COUNT(*)')
            ->from('user_password_resets')
            ->where('token = :token')
            ->setParameter(':token', $token)
            ->execute()->fetchOne();
    }
}