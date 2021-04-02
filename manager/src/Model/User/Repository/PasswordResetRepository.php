<?php

namespace App\Model\User\Repository;

use App\Model\User\Entity\Password\PasswordReset;
use App\Model\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PasswordResetRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(PasswordReset::class);
    }

    public function findByUser(User $user): ?PasswordReset
    {
        return $this->repo->findOneBy(['user' => $user]) ?: null;
    }

    public function add(PasswordReset $passwordReset): void
    {
        $this->em->persist($passwordReset);
    }

    public function remove(PasswordReset $passwordReset): void
    {
        $this->em->remove($passwordReset);
    }
}