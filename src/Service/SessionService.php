<?php

namespace App\Service;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;

class SessionService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(): Session
    {
        $session = new Session();

        $this->entityManager->persist($session);
        $this->entityManager->flush();

        return $session;
    }
}
