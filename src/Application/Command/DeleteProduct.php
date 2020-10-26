<?php

declare(strict_types=1);

namespace Application\Command;

/**
 * Class DeleteProduct
 * @package Application\Command
 */
final class DeleteProduct
{
    private string $productId;

    public function __construct(string $productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function productId(): string
    {
        return $this->productId;
    }
}
