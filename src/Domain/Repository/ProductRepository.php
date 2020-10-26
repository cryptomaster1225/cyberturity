<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Exception\ProductNotFound;
use Domain\Model\Product;

/**
 * Class ProductRepository
 * @package Domain\Repository
 */
interface ProductRepository
{
    /**
     * @param Product $entity
     */
    public function save(Product $entity): void;

    /**
     * @param Product $entity
     */
    public function delete(Product $entity): void;

    /**
     * @param string $id
     * @return Product
     * @throws ProductNotFound
     */
    public function find(string $id): Product;

    /**
     * @return array
     */
    public function findAll(): array;
}
