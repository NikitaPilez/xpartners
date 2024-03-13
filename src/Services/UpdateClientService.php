<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class UpdateClientService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(Client $client, string $username = null, string $email = null, string $phone = null): Client
    {
        if ($username) {
            $client->setUsername($username);
        }

        if ($phone) {
            $client->setPhone($phone);
        }

        if ($email) {
            $client->setEmail($email);
        }

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client;
    }
}