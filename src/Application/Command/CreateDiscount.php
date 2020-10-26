<?php

declare(strict_types=1);

namespace Application\Command;

/**
 * Class CreateDiscount
 * @package Application\Command
 */
final class CreateDiscount
{
    private string $discountId;

    private string $code;

    private string $kind;

    private float $amount;

    /**
     * @param string $discountId
     * @param string $code
     * @param string $kind
     * @param float  $amount
     */
    public function __construct(string $discountId, string $code, string $kind, float $amount)
    {
        $this->discountId = $discountId;
        $this->code = $code;
        $this->kind = $kind;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function discountId(): string
    {
        return $this->discountId;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function kind(): string
    {
        return $this->kind;
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }
}
