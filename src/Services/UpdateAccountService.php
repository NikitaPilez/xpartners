<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;

class UpdateAccountService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(Account $account, bool $isActive = null): Account
    {
        if (isset($isActive)) {
            $account->setActive($isActive);
        }

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }
}