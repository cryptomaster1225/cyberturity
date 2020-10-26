<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\CreateProduct;
use Domain\Model\Product;
use Domain\Repository\ProductRepository;

/**
 * Class CreateProductHandler
 * @package Application\CommandHandler
 */
final class CreateProductHandler
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
     * @param CreateProduct $command
     */
    public function __invoke(CreateProduct $command): void
    {
        $product = new Product(
            $command->productId(),
            $command->name(),
            $command->price(),
            'AUD'
        );

        $this->productRepository->save($product);
    }
}
