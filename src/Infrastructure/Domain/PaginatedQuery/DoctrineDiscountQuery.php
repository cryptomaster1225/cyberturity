<?php

declare(strict_types=1);

namespace Infrastructure\Domain\PaginatedQuery;

use Doctrine\ORM\Query;
use Domain\PaginatedQuery\DiscountFilter;
use Domain\PaginatedQuery\DiscountQuery;
use Infrastructure\Domain\Repository\DoctrineDiscountRepository;

/**
 * Class DoctrineDiscountQuery
 * @package Infrastructure\Domain\PaginatedQuery
 */
class DoctrineDiscountQuery implements DiscountQuery
{
    /**
     * @var DoctrineDiscountRepository
     */
    private DoctrineDiscountRepository $discountRepository;

    /**
     * @param DoctrineDiscountRepository $discountRepository
     */
    public function __construct(DoctrineDiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @inheritDoc
     */
    public function find(DiscountFilter $filter): Query
    {
        $query = $this->discountRepository->createQueryBuilder('o')
            ->orderBy('o.code', 'ASC')
            ->setFirstResult(($filter->page() - 1) * $filter->perPage())
            ->setMaxResults($filter->perPage());

        return $query->getQuery();
    }
}
