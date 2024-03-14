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
use OpenApi\Attributes as OA;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    #[OA\Get(
        description: 'Get all clients',
    )]
    #[OA\Response(
        response: 200,
        description: 'Return all clients',
//        content: new OA\JsonContent(
//            type: 'array',
//            items: new OA\Items(ref: new Model(type: Account::class, groups: ['account', 'client']))
//        )
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'clients')]
    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->json(data: $clientRepository->findAll(), context: ['groups' => 'client']);
    }

    #[OA\Post(
        description: 'Create new client',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'username',
                    'phone',
                    'email',
                ],
                properties: [
                    new OA\Property(
                        property: 'username', description: 'Username', type: 'string', example: 'Nik',
                    ),
                    new OA\Property(
                        property: 'phone', description: 'Phone', type: 'string', example: '+375297777777',
                    ),
                    new OA\Property(
                        property: 'email', description: 'Email', type: 'string', example: 'test@gmail.com',
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Return new client',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'clients')]
    #[Route('/', name: 'client_new', methods: ['POST'])]
    public function new(CreateClientRequest $request, CreateClientService $clientService): Response
    {
        $createClientDTO = CreateClientDTO::fromRequest($request);
        $client = $clientService->create($createClientDTO->username, $createClientDTO->email, $createClientDTO->phone);

        return $this->json(data: $client, context: ['groups' => 'client']);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns client by id',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'clients')]
    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client = null): Response
    {
        if (!$client) {
            throw new NotFoundException(['Not found client']);
        }

        return $this->json(data: $client, context: ['groups' => 'client']);
    }

    #[OA\Post(
        description: 'Update client',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'username', description: 'Username', type: 'string', example: 'Nik',
                    ),
                    new OA\Property(
                        property: 'phone', description: 'Phone', type: 'string', example: '+375297777777',
                    ),
                    new OA\Property(
                        property: 'email', description: 'Email', type: 'string', example: 'test@gmail.com',
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Return updated client',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'clients')]
    #[Route('/{id}/edit', name: 'client_edit', methods: ['POST'])]
    public function edit(UpdateClientRequest $request, UpdateClientService $updateClientService, Client $client = null): Response
    {
        if (!$client) {
            throw new NotFoundException(['Not found client']);
        }

        $updateClientDTO = UpdateClientDTO::fromRequest($request);
        $client = $updateClientService->update($client, $updateClientDTO->username, $updateClientDTO->email, $updateClientDTO->phone);
        return $this->json(data: $client, context: ['groups' => 'client']);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns message that success deleted',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'clients')]
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
