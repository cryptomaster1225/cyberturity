<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\EditDiscount;
use Domain\Repository\DiscountRepository;

/**
 * Class EditDiscountHandler
 * @package Application\CommandHandler
 */
final class EditDiscountHandler
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
     * @param EditDiscount $command
     */
    public function __invoke(EditDiscount $command): void
    {
        $discount = $this->discountRepository->find($command->discountId());

        $discount->changeCode($command->code());
        $discount->changeKind($command->kind());
        $discount->changeAmount($command->amount());

        $this->discountRepository->save($discount);
    }
}
