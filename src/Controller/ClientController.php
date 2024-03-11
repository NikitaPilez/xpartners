<?php

namespace App\Controller;

use App\DTO\CreateClientDTO;
use App\DTO\UpdateClientDTO;
use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Request\CreateClientRequest;
use App\Request\UpdateClientRequest;
use App\Services\CreateClientService;
use App\Services\UpdateClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): JsonResponse
    {
        return $this->json($clientRepository->findAll());
    }

    #[Route('/', name: 'client_new', methods: ['POST'])]
    public function new(CreateClientRequest $request, CreateClientService $clientService): Response
    {
        $createClientDTO = CreateClientDTO::fromRequest($request);
        $client = $clientService->create($createClientDTO->username, $createClientDTO->email, $createClientDTO->phone);

        return $this->json($client);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client = null): JsonResponse
    {
        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        return $this->json($client);
    }

    #[Route('/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(UpdateClientRequest $request, Client $client, UpdateClientService $updateClientService): Response
    {
        $updateClientDTO = UpdateClientDTO::fromRequest($request);
        $client = $updateClientService->update($client, $updateClientDTO->username, $updateClientDTO->phone, $updateClientDTO->email);
        return $this->json($client);
    }

    #[Route('/{id}', name: 'client_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Client $client = null): Response
    {
        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        $entityManager->remove($client);
        $entityManager->flush();

        return $this->json(['message' => 'Success deleted']);
    }
}
