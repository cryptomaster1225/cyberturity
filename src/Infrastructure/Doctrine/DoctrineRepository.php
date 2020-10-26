<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait DoctrineRepository
 * @package Infrastructure\Doctrine
 */
trait DoctrineRepository
{
    /**
     * @var ManagerRegistry
     */
    protected ManagerRegistry $managerRegistry;

    /**
     * @var string
     */
    protected string $entityName;

    /**
     * @return EntityManager
     * @throws \DomainException
     */
    public function getEntityManager(): EntityManager
    {
        $manager = $this->managerRegistry->getManagerForClass($this->entityName);

        if (!$manager instanceof EntityManager) {
            throw new \DomainException('Manager is not instance of \Doctrine\ORM\EntityManager');
        }

        return $manager;
    }

    /**
     * @param $alias
     * @return QueryBuilder
     * @throws \DomainException
     */
    public function createQueryBuilder($alias): QueryBuilder
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->entityName, $alias);
    }
}
