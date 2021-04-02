<?php

namespace App\Model\User\Repository;

use App\Model\EntityNotFoundException;
use App\Model\User\Entity\PasswordChange;
use App\Model\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

class PasswordChangeRepository
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
        $this->repo = $em->getRepository(PasswordChange::class);
    }

    public function add(PasswordChange $passwordChange): void
    {
        $this->em->persist($passwordChange);
    }

    public function getByToken(string $token): PasswordChange
    {
        if(!$passwordChange = $this->repo->findOneBy(['token' => $token])) {
            throw new EntityNotFoundException();
        }

        return $passwordChange;
    }

    public function findByUser(User $user): ?PasswordChange
    {
        return $this->repo->findOneBy(['user' => $user]) ?: null;
    }

    public function remove(PasswordChange $passwordChange): void
    {
        $this->em->remove($passwordChange);
    }
}