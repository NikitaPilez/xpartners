<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\PaymentResultDTO;
use App\Entity\Transaction;
use App\Exception\InvalidJsonRequestException;
use App\Exception\NotFoundException;
use App\Helpers\Exchange;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MakePaymentService
{
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $entityManager)
    {
        $this->accountRepository = $accountRepository;
        $this->entityManager = $entityManager;
    }

    public function pay(int $senderId, int $receiverId, float $value, string $message): PaymentResultDTO
    {
        $sender = $this->accountRepository->find($senderId);
        $receiver = $this->accountRepository->find($receiverId);

        if (!$sender || !$receiver) {
            throw new NotFoundException(['Not correct sender or receiver id']);
        }

        if (!$sender->isActive() || !$receiver->isActive()) {
            throw new InvalidJsonRequestException(['Account is not active']);
        }

        if ($value > $sender->getCoin()) {
            throw new InvalidJsonRequestException(['Value large']);
        }

        $exchangeValue = Exchange::run($sender->getCurrency(), $receiver->getCurrency(), $value);

        $this->entityManager->beginTransaction();

        try {
            $sender->setCoin($sender->getCoin() - $value);
            $this->entityManager->persist($sender);

            $receiver->setCoin($receiver->getCoin() + $exchangeValue);
            $this->entityManager->persist($receiver);

            $transaction = new Transaction();
            $transaction->setSender($sender);
            $transaction->setReceiver($receiver);
            $transaction->setComment($message);
            $transaction->setValue($value);

            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return new PaymentResultDTO(message: 'success', isSuccess: true);
        } catch (Exception $exception) {
            $this->entityManager->rollback();

            return new PaymentResultDTO(message: $exception->getMessage(), isSuccess: false);
        }
    }
}