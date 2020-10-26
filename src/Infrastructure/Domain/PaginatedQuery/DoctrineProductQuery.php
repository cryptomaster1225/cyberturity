<?php

declare(strict_types=1);

namespace Infrastructure\Domain\PaginatedQuery;

use Doctrine\ORM\Query;
use Domain\PaginatedQuery\ProductFilter;
use Domain\PaginatedQuery\ProductQuery;
use Infrastructure\Domain\Repository\DoctrineProductRepository;

/**
 * Class DoctrineProductQuery
 * @package Infrastructure\Domain\PaginatedQuery
 */
class DoctrineProductQuery implements ProductQuery
{
    /**
     * @var DoctrineProductRepository
     */
    private DoctrineProductRepository $productRepository;

    /**
     * @param DoctrineProductRepository $productRepository
     */
    public function __construct(DoctrineProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritDoc
     */
    public function find(ProductFilter $filter): Query
    {
        $query = $this->productRepository->createQueryBuilder('o')
            ->orderBy('o.name', 'ASC')
            ->setFirstResult(($filter->page() - 1) * $filter->perPage())
            ->setMaxResults($filter->perPage());

        return $query->getQuery();
    }
}
