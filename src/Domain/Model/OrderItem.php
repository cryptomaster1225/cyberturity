<?php

declare(strict_types=1);

namespace Domain\Model;

use Ramsey\Uuid\Uuid;

/**
 * Class OrderItem
 * @package Domain\Model
 */
class OrderItem
{
    private string $id;

    /**
     * @var Order
     */
    private Order $order;

    /**
     * @var Product
     */
    private Product $product;

    private int $quantity;

    private float $unitPrice;

    /**
     * @param Order   $order
     * @param Product $product
     * @param int     $quantity
     */
    public function __construct(Order $order, Product $product, int $quantity)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->order = $order;
        $this->product = $product;
        $this->quantity = $quantity;

        $unitPrice = $product->price();
        if ($order->discountAmount() && $order->discountKind() === '%') {
            $discountPercent = $order->discountAmount() / 100;
            $discountRate = 1 - $discountPercent;

            $unitPrice = round($discountRate * $product->price(), 2);
        }

        $this->unitPrice = $unitPrice;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return Order
     */
    public function order(): Order
    {
        return $this->order;
    }

    /**
     * @return Product
     */
    public function product(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function unitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @return float
     */
    public function subtotal(): float
    {
        return $this->unitPrice() * $this->quantity();
    }
}
