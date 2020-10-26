<?php

declare(strict_types=1);

namespace Domain\PaginatedQuery;

use Doctrine\ORM\Query;

/**
 * Class DiscountQuery
 * @package Domain\PaginatedQuery
 */
interface DiscountQuery
{
    /**
     * @param DiscountFilter $filter
     * @return Query
     */
    public function find(DiscountFilter $filter): Query;
}
