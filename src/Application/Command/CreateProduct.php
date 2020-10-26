<?php

declare(strict_types=1);

namespace Application\Command;

/**
 * Class CreateProduct
 * @package Application\Command
 */
class CreateProduct
{
    private string $productId;

    private string $name;

    private float $price;

    /**
     * @param string $productId
     * @param string $name
     * @param float  $price
     */
    public function __construct(
        string $productId,
        string $name,
        float $price
    ) {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function productId(): string
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return $this->price;
    }
}
