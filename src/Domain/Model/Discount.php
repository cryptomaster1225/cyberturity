<?php

declare(strict_types=1);

namespace Domain\Model;

/**
 * Class Discount
 * @package Domain\Model
 */
class Discount
{
    private string $id;

    private string $code;

    private string $kind;

    private float $amount;

    /**
     * @param string $id
     * @param string $code
     * @param string $kind
     * @param float  $amount
     */
    public function __construct(string $id, string $code, string $kind, float $amount)
    {
        $this->id = $id;
        $this->code = $code;
        $this->kind = $kind;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function changeCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function kind(): string
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     */
    public function changeKind(string $kind): void
    {
        $this->kind = $kind;
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function changeAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
