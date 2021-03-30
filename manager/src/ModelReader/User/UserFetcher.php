<?php

namespace App\ModelReader\User;

use Doctrine\DBAL\Connection;

class UserFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetchForAuth(string $login): ?array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('id', 'email', 'login', 'password', 'role', 'status')
            ->from('user_users')
            ->where('login = :login')
            ->setParameter(':login', $login)
            ->execute();

        return $stmt->fetchAssociative() ?: null;
    }
}