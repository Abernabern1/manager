<?php

namespace App\Model\User\Repository;

use App\Model\EntityNotFoundException;
use App\Model\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

class UserRepository
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
        $this->repo = $em->getRepository(User::class);
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function getByToken(string $token): User
    {
        if(!$user = $this->repo->findOneBy(['confirmToken' => $token])) {
            throw new EntityNotFoundException();
        }

        return $user;
    }
}