<?php

declare(strict_types=1);

namespace Domain\PaginatedQuery;

use Doctrine\ORM\Query;

/**
 * Class ProductQuery
 * @package Domain\PaginatedQuery
 */
interface ProductQuery
{
    /**
     * @param ProductFilter $filter
     * @return Query
     */
    public function find(ProductFilter $filter): Query;
}
