<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Domain\Exception\ProductNotFound;
use Domain\Model\Product;
use Domain\Repository\ProductRepository;
use Infrastructure\Doctrine\DoctrineRepository;

/**
 * Class DoctrineProductRepository
 * @package Infrastructure\Domain\Repository
 */
class DoctrineProductRepository implements ProductRepository
{
    use DoctrineRepository;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityName = Product::class;
    }

    public function save(Product $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function delete(Product $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function find(string $id): Product
    {
        $entity = $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity) {
            throw ProductNotFound::withId($id);
        }

        return $entity;
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('o')->getQuery()->getResult();
    }
}
