<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\EditProduct;
use Domain\Model\Product;
use Domain\Repository\ProductRepository;

/**
 * Class EditProductHandler
 * @package Application\CommandHandler
 */
final class EditProductHandler
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
     * @param EditProduct $command
     */
    public function __invoke(EditProduct $command): void
    {
        $product = $this->productRepository->find($command->productId());

        $product->changeName($command->name());
        $product->changePrice($command->price());

        $this->productRepository->save($product);
    }
}
