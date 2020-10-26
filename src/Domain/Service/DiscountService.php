<?php

declare(strict_types=1);

namespace Domain\Service;

use Domain\Exception\DiscountNotFound;
use Domain\Model\Discount;
use Domain\Model\Product;
use Domain\Repository\DiscountRepository;

/**
 * Class DiscountService
 * @package Domain\Service
 */
class DiscountService
{
    /**
     * @var DiscountRepository
     */
    private DiscountRepository $discountRepository;

    /**
     * @param DiscountRepository $discountRepository
     */
    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param Product     $product
     * @param int         $quantity
     * @param string|null $code
     * @return array
     */
    public function calculateDiscount(Product $product, int $quantity, ?string $code): array
    {
        $discount = 0.0;
        $discountCode = null;

        if ($code) {
            try {
                $discountCode = $this->discountRepository->findByCode($code);
            } catch (DiscountNotFound $e) {
                $discountCode = null;
            }
        }

        $subtotal = $product->price() * $quantity;
        if ($discountCode instanceof Discount) {
            if ($discountCode->kind() === '%') {
                $discountPercent = $discountCode->amount() / 100;
                $discountRate = 1 - $discountPercent;
                $unitPrice = round($discountRate * $product->price(), 2);

                $discount = $subtotal - round($unitPrice * $quantity, 2);
            } else {
                $discount = $discountCode->amount();
            }
        }

        $total = $discount > 0 ? $subtotal - $discount : $subtotal;
        $total = $total < 0 ? 0 : $total;

        return [
            'subtotal'      => $subtotal,
            'discount'      => $discount,
            'total'         => $total,
            'discountValid' => $discountCode instanceof Discount,
        ];
    }
}
