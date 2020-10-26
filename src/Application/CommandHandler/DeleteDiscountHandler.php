<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\DeleteDiscount;
use Domain\Repository\DiscountRepository;

/**
 * Class DeleteDiscountHandler
 * @package Application\CommandHandler
 */
final class DeleteDiscountHandler
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
     * @param DeleteDiscount $command
     */
    public function __invoke(DeleteDiscount $command): void
    {
        $discount = $this->discountRepository->find($command->discountId());

        $this->discountRepository->delete($discount);
    }
}
