<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\DeleteProduct;
use Domain\Repository\ProductRepository;

/**
 * Class DeleteProductHandler
 * @package Application\CommandHandler
 */
final class DeleteProductHandler
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param DeleteProduct $command
     */
    public function __invoke(DeleteProduct $command): void
    {
        $discount = $this->productRepository->find($command->productId());

        $this->productRepository->delete($discount);
    }
}
