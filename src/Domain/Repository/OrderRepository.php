<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Exception\OrderNotFound;
use Domain\Model\Order;

/**
 * Interface OrderRepository
 * @package Domain\Repository
 */
interface OrderRepository
{
    /**
     * @param Order $entity
     */
    public function save(Order $entity): void;

    /**
     * @param Order $entity
     */
    public function delete(Order $entity): void;

    /**
     * @param string $id
     * @return Order
     * @throws OrderNotFound
     */
    public function find(string $id): Order;

    /**
     * @param string $paypalId
     * @return Order
     * @throws OrderNotFound
     */
    public function findByPaypalId(string $paypalId): Order;
}
