<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Domain\Exception\DiscountNotFound;
use Domain\Model\Discount;
use Domain\Repository\DiscountRepository;
use Infrastructure\Doctrine\DoctrineRepository;

/**
 * Class DoctrineDiscountRepository
 * @package Infrastructure\Domain\Repository
 */
class DoctrineDiscountRepository implements DiscountRepository
{
    use DoctrineRepository;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityName = Discount::class;
    }

    public function save(Discount $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function delete(Discount $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function find(string $id): Discount
    {
        $entity = $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            throw DiscountNotFound::withId($id);
        }

        return $entity;
    }

    public function findByCode(string $code): Discount
    {
        $entity = $this->createQueryBuilder('o')
            ->andWhere('o.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            throw DiscountNotFound::withCode($code);
        }

        return $entity;
    }
}
