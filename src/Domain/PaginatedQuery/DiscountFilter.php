<?php

declare(strict_types=1);

namespace Domain\PaginatedQuery;

/**
 * Class DiscountFilter
 * @package Domain\PaginatedQuery
 */
final class DiscountFilter
{
    private int $page;

    private int $perPage;

    public function __construct()
    {
        $this->page = 1;
        $this->perPage = 20;
    }

    /**
     * @return int
     */
    public function page(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function changePage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function changePerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }
}
