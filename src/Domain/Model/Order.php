<?php

declare(strict_types=1);

namespace Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Order
 * @package Domain\Model
 */
class Order
{
    private string $id;

    private string $email;

    private string $firstName;

    private string $lastName;

    private string $currency = 'AUD';

    private ?string $companyName;

    private string $addressLine1;

    private ?string $addressLine2;

    private string $city;

    private string $postalCode;

    private string $country;

    private ?string $paypalId = null;

    private ?string $paypalStatus = null;

    private ?string $invoiceId = null;

    private ?string $invoiceStatus = null;

    private ?string $discountCode = null;

    private ?string $discountKind = null;

    private ?float $discountAmount = null;

    private Collection $items;

    public function __construct(
        string $id,
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
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->companyName = $companyName;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->country = $country;
        $this->items = new ArrayCollection();
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

    /**
     * @return string|null
     */
    public function paypalId(): ?string
    {
        return $this->paypalId;
    }

    /**
     * @param string|null $paypalId
     */
    public function changePaypalId(?string $paypalId): void
    {
        $this->paypalId = $paypalId;
    }

    /**
     * @return string|null
     */
    public function paypalStatus(): ?string
    {
        return $this->paypalStatus;
    }

    /**
     * @param string|null $paypalStatus
     */
    public function changePaypalStatus(?string $paypalStatus): void
    {
        $this->paypalStatus = $paypalStatus;
    }

    /**
     * @return string|null
     */
    public function invoiceId(): ?string
    {
        return $this->invoiceId;
    }

    /**
     * @param string|null $invoiceId
     */
    public function changeInvoiceId(?string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * @return string|null
     */
    public function invoiceStatus(): ?string
    {
        return $this->invoiceStatus;
    }

    /**
     * @param string|null $invoiceStatus
     */
    public function changeInvoiceStatus(?string $invoiceStatus): void
    {
        $this->invoiceStatus = $invoiceStatus;
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

    /**
     * @return string|null
     */
    public function discountCode(): ?string
    {
        return $this->discountCode;
    }

    /**
     * @param string|null $discountCode
     */
    public function changeDiscountCode(?string $discountCode): void
    {
        $this->discountCode = $discountCode;
    }

    /**
     * @return string|null
     */
    public function discountKind(): ?string
    {
        return $this->discountKind;
    }

    /**
     * @param string|null $discountKind
     */
    public function changeDiscountKind(?string $discountKind): void
    {
        $this->discountKind = $discountKind;
    }

    /**
     * @return float|null
     */
    public function discountAmount(): ?float
    {
        if ($this->discountAmount === null) {
            return null;
        }

        return (float)$this->discountAmount;
    }

    /**
     * @param float|null $discountAmount
     */
    public function changeDiscountAmount(?float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @return float
     */
    public function total(): float
    {
        $total = 0.0;
        /** @var OrderItem $item */
        foreach ($this->items() as $item) {
            $total += $item->subtotal();
        }

        if ($this->discountAmount() && $this->discountKind() === $this->currency()) {
            $total -= $this->discountAmount();

            if ($total < 0) {
                $total = 0.0;
            }
        }

        return $total;
    }
}
