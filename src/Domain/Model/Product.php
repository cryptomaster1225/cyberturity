<?php

declare(strict_types=1);

namespace Domain\Model;

/**
 * Class Product
 * @package Domain\Model
 */
class Product
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @param string $id
     * @param string $name
     * @param float  $price
     * @param string $currency
     */
    public function __construct(string $id, string $name, float $price, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function changeId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function price(): float
    {
        return (float)$this->price;
    }

    /**
     * @param float $price
     */
    public function changePrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function changeCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
