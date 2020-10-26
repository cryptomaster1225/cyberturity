<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\CreateDiscount;
use Domain\Model\Discount;
use Domain\Repository\DiscountRepository;

/**
 * Class CreateDiscountHandler
 * @package Application\CommandHandler
 */
final class CreateDiscountHandler
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
     * @param CreateDiscount $command
     */
    public function __invoke(CreateDiscount $command): void
    {
        $discount = new Discount(
            $command->discountId(),
            $command->code(),
            $command->kind(),
            $command->amount()
        );

        $this->discountRepository->save($discount);
    }
}
