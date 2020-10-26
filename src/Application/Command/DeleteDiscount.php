<?php

declare(strict_types=1);

namespace Application\Command;

/**
 * Class DeleteDiscount
 * @package Application\Command
 */
final class DeleteDiscount
{
    private string $discountId;

    public function __construct(string $discountId)
    {
        $this->discountId = $discountId;
    }

    /**
     * @return string
     */
    public function discountId(): string
    {
        return $this->discountId;
    }
}
