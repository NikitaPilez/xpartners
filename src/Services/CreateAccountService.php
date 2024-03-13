<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Account;
use App\Exception\NotFoundException;
use App\Helpers\GenerateAccountNumber;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateAccountService
{
    private AccountRepository $accountRepository;
    private ClientRepository $clientRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AccountRepository $accountRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager)
    {
        $this->accountRepository = $accountRepository;
        $this->clientRepository = $clientRepository;
        $this->entityManager = $entityManager;
    }

    public function create(int $clientId, string $currency): Account
    {
        $client = $this->clientRepository->find($clientId);

        if (!$client) {
            throw new NotFoundException(['Not found client']);
        }

        $accountByCurrency = $this->accountRepository->findByClientAndCurrency($client, $currency);

        if ($accountByCurrency) {
            throw new NotFoundException(['Account with this currency already exist']);
        }

        $account = new Account();
        $account->setActive(true);
        $account->setClient($client);
        $account->setCurrency($currency);
        $account->setCoin(rand(100, 1000));
        $account->setNumber(GenerateAccountNumber::generate());

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }
}