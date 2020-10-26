<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Domain\Exception\OrderNotFound;
use Domain\Model\Order;
use Domain\Repository\OrderRepository;
use Infrastructure\Doctrine\DoctrineRepository;

/**
 * Class DoctrineOrderRepository
 * @package Infrastructure\Domain\Repository
 */
class DoctrineOrderRepository implements OrderRepository
{
    use DoctrineRepository;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityName = Order::class;
    }

    public function save(Order $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function delete(Order $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function find(string $id): Order
    {
        $entity = $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            throw OrderNotFound::withId($id);
        }

        return $entity;
    }

    public function findByPaypalId(string $paypalId): Order
    {
        $entity = $this->createQueryBuilder('o')
            ->andWhere('o.paypalId = :paypalId')
            ->setParameter('paypalId', $paypalId)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            throw OrderNotFound::withPaypalId($paypalId);
        }

        return $entity;
    }
}
