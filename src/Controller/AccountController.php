<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateAccountDTO;
use App\DTO\MakePayDTO;
use App\DTO\UpdateAccountDTO;
use App\Entity\Account;
use App\Exception\NotFoundException;
use App\Repository\AccountRepository;
use App\Request\CreateAccountRequest;
use App\Request\MakePayRequest;
use App\Request\UpdateAccountRequest;
use App\Services\CreateAccountService;
use App\Services\MakePaymentService;
use App\Services\UpdateAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/account')]
class AccountController extends AbstractController
{
    #[OA\Get(
        description: 'Get all accounts',
    )]
    #[OA\Response(
        response: 200,
        description: 'Return all accounts',
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
    #[OA\Tag(name: 'accounts')]
    #[Route('/', name: 'account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->json(data: $accountRepository->findAll(), context: ['groups' => ['account', 'client']]);
    }

    #[OA\Post(
        description: 'Create new account for client',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'client_id',
                    'currency',
                ],
                properties: [
                    new OA\Property(
                        property: 'client_id', description: 'ID client', type: 'integer', example: 1,
                    ),
                    new OA\Property(
                        property: 'currency', description: 'Currency key (USD/EUR/RUB)', type: 'string', example: 'EUR',
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Return new account',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'accounts')]
    #[Route('/', name: 'account_new', methods: ['POST'])]
    public function new(CreateAccountRequest $request, CreateAccountService $createAccountService): Response
    {
        $createAccountDTO = CreateAccountDTO::fromRequest($request);
        $account = $createAccountService->create($createAccountDTO->clientId, $createAccountDTO->currency);

        return $this->json(data: $account, context: ['groups' => ['account', 'client']]);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns account by id',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'accounts')]
    #[Route('/{id}', name: 'account_show', methods: ['GET'])]
    public function show(Account $account = null): Response
    {
        if (!$account) {
            throw new NotFoundException(['Not found account']);
        }

        return $this->json(data: $account, context: ['groups' => ['account', 'client']]);
    }

    #[OA\Post(
        description: 'Update account',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'is_active', description: 'Active/deactivate account', type: 'boolean', example: false,
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Return updated account',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'accounts')]
    #[Route('/{id}/edit', name: 'account_edit', methods: ['POST'])]
    public function edit(UpdateAccountRequest $request, UpdateAccountService $updateAccountService, Account $account = null): Response
    {
        if (!$account) {
            throw new NotFoundException(['Not found account']);
        }

        $updateAccountDTO = UpdateAccountDTO::fromRequest($request);
        $account = $updateAccountService->update($account, $updateAccountDTO->isActive);

        return $this->json(data: $account, context: ['groups' => ['account', 'client']]);
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
    #[OA\Tag(name: 'accounts')]
    #[Route('/{id}', name: 'account_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Account $account = null): Response
    {
        if (!$account) {
            throw new NotFoundException(['Not found account']);
        }

        $entityManager->remove($account);
        $entityManager->flush();

        return $this->json(['message' => 'Success deleted']);
    }

    #[OA\Post(
        description: 'Make payment',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: [
                    'sender_id',
                    'receiver_id',
                    'value',
                ],
                properties: [
                    new OA\Property(
                        property: 'sender_id', description: 'ID sender', type: 'integer', example: 1,
                    ),
                    new OA\Property(
                        property: 'receiver_id', description: 'ID receiver', type: 'integer', example: 2,
                    ),
                    new OA\Property(
                        property: 'value', description: 'Value', type: 'float', example: '9.99',
                    ),
                    new OA\Property(
                        property: 'comment', description: 'Comment', type: 'string', example: 'Gift',
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Return pay result',
    )]
    #[OA\Parameter(
        name: 'AUTH-TOKEN',
        description: 'API key',
        in: 'header',
        required: true,
    )]
    #[OA\Tag(name: 'accounts')]
    #[Route('/pay', name: 'account_pay', methods: ['POST'])]
    public function pay(MakePayRequest $request, MakePaymentService $makePaymentService): Response
    {
        $makePayDTO = MakePayDTO::fromRequest($request);
        $paymentResultDTO = $makePaymentService->pay($makePayDTO->senderId, $makePayDTO->receiverId, $makePayDTO->value, $makePayDTO->comment);

        return $this->json([
            'message' => $paymentResultDTO->message,
            'success' => $paymentResultDTO->isSuccess,
        ]);
    }
}
