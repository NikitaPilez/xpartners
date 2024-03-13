<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findByClientAndCurrency(Client $client, string $currency): ?Account
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.client = :client')
            ->andWhere('a.currency = :currency')
            ->setParameter('client', $client)
            ->setParameter('currency', $currency)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
