<?php

namespace App\Services;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class CreateClientService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(string $username, string $email, string $phone): Client
    {
        $client = new Client();
        $client->setEmail($email);
        $client->setUsername($username);
        $client->setPhone($phone);
        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}