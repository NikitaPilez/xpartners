<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateClientDTO;
use App\DTO\UpdateClientDTO;
use App\Entity\Client;
use App\Exception\NotFoundException;
use App\Repository\ClientRepository;
use App\Request\CreateClientRequest;
use App\Request\UpdateClientRequest;
use App\Services\CreateClientService;
use App\Services\UpdateClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->json(data: $clientRepository->findAll(), context: ['groups' => 'client']);
    }

    #[Route('/', name: 'client_new', methods: ['POST'])]
    public function new(CreateClientRequest $request, CreateClientService $clientService): Response
    {
        $createClientDTO = CreateClientDTO::fromRequest($request);
        $client = $clientService->create($createClientDTO->username, $createClientDTO->email, $createClientDTO->phone);

        return $this->json(data: $client, context: ['groups' => 'client']);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client = null): Response
    {
        if (!$client) {
            throw new NotFoundException(['Not found client']);
        }

        return $this->json(data: $client, context: ['groups' => 'client']);
    }

    #[Route('/{id}/edit', name: 'client_edit', methods: ['POST'])]
    public function edit(UpdateClientRequest $request, UpdateClientService $updateClientService, Client $client = null): Response
    {
        if (!$client) {
            throw new NotFoundException(['Not found client']);
        }

        $updateClientDTO = UpdateClientDTO::fromRequest($request);
        $client = $updateClientService->update($client, $updateClientDTO->username, $updateClientDTO->phone, $updateClientDTO->email);
        return $this->json(data: $client, context: ['groups' => 'client']);
    }

    #[Route('/{id}', name: 'client_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Client $client = null): Response
    {
        if (!$client) {
            throw new NotFoundException(['Not found client']);
        }

        $entityManager->remove($client);
        $entityManager->flush();

        return $this->json(['message' => 'Success deleted']);
    }
}
