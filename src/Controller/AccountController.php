<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreateAccountDTO;
use App\DTO\UpdateAccountDTO;
use App\Entity\Account;
use App\Exception\NotFoundException;
use App\Repository\AccountRepository;
use App\Request\CreateAccountRequest;
use App\Request\UpdateAccountRequest;
use App\Services\CreateAccountService;
use App\Services\UpdateAccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->json(data: $accountRepository->findAll(), context: ['groups' => ['account', 'client']]);
    }

    #[Route('/', name: 'account_new', methods: ['POST'])]
    public function new(CreateAccountRequest $request, CreateAccountService $createAccountService): Response
    {
        $createAccountDTO = CreateAccountDTO::fromRequest($request);
        $account = $createAccountService->create($createAccountDTO->clientId, $createAccountDTO->currency);

        return $this->json(data: $account, context: ['groups' => ['account', 'client']]);
    }

    #[Route('/{id}', name: 'account_show', methods: ['GET'])]
    public function show(Account $account = null): Response
    {
        if (!$account) {
            throw new NotFoundException(['Not found account']);
        }

        return $this->json(data: $account, context: ['groups' => ['account', 'client']]);
    }

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
}
