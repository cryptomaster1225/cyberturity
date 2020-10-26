<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Exception\DiscountNotFound;
use Domain\Model\Discount;

/**
 * Interface DiscountRepository
 * @package Domain\Repository
 */
interface DiscountRepository
{
    /**
     * @param Discount $entity
     */
    public function save(Discount $entity): void;

    /**
     * @param Discount $entity
     */
    public function delete(Discount $entity): void;

    /**
     * @param string $id
     * @return Discount
     * @throws DiscountNotFound
     */
    public function find(string $id): Discount;

    /**
     * @param string $code
     * @return Discount
     * @throws DiscountNotFound
     */
    public function findByCode(string $code): Discount;
}
