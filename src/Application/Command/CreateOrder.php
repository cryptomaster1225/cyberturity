<?php

declare(strict_types=1);

namespace Application\Command;

/**
 * Class CreateOrder
 * @package Application\Command
 */
final class CreateOrder
{
    private string $orderId;

    private string $productId;

    private string $email;

    private string $firstName;

    private string $lastName;

    private int $quantity;

    private ?string $discountCode;

    private ?string $companyName;

    private string $addressLine1;

    private ?string $addressLine2;

    private string $city;

    private string $postalCode;

    private string $country;

    /**
     * @param string      $orderId
     * @param string      $productId
     * @param int         $quantity
     * @param string|null $discountCode
     * @param string      $email
     * @param string      $firstName
     * @param string      $lastName
     * @param string|null $companyName
     * @param string      $addressLine1
     * @param string|null $addressLine2
     * @param string      $city
     * @param string      $postalCode
     * @param string      $country
     */
    public function __construct(
        string $orderId,
        string $productId,
        int $quantity,
        ?string $discountCode,
        string $email,
        string $firstName,
        string $lastName,
        ?string $companyName,
        string $addressLine1,
        ?string $addressLine2,
        string $city,
        string $postalCode,
        string $country

    ) {
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->discountCode = $discountCode;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->companyName = $companyName;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function orderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function productId(): string
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function discountCode(): ?string
    {
        return $this->discountCode;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function companyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function addressLine1(): string
    {
        return $this->addressLine1;
    }

    /**
     * @return string|null
     */
    public function addressLine2(): ?string
    {
        return $this->addressLine2;
    }

    /**
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function postalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function country(): string
    {
        return $this->country;
    }
}
