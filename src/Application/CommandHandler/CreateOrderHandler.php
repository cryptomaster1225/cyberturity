<?php

declare(strict_types=1);

namespace Application\CommandHandler;

use Application\Command\CreateOrder;
use Domain\Exception\DiscountNotFound;
use Domain\Model\Order;
use Domain\Model\OrderItem;
use Domain\Repository\DiscountRepository;
use Domain\Repository\OrderRepository;
use Domain\Repository\ProductRepository;

/**
 * Class CreateOrderHandler
 * @package Application\CommandHandler
 */
final class CreateOrderHandler
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var DiscountRepository
     */
    private DiscountRepository $discountRepository;

    /**
     * @param OrderRepository    $orderRepository
     * @param ProductRepository  $productRepository
     * @param DiscountRepository $discountRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        DiscountRepository $discountRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param CreateOrder $command
     */
    public function __invoke(CreateOrder $command): void
    {
        $order = new Order(
            $command->orderId(),
            $command->email(),
            $command->firstName(),
            $command->lastName(),
            $command->companyName(),
            $command->addressLine1(),
            $command->addressLine2(),
            $command->city(),
            $command->postalCode(),
            $command->country()
        );

        if ($command->discountCode()) {
            try {
                $discount = $this->discountRepository->findByCode($command->discountCode());

                $order->changeDiscountCode($discount->code());
                $order->changeDiscountAmount($discount->amount());
                $order->changeDiscountKind($discount->kind());
            } catch (DiscountNotFound $e) {
            }
        }

        $product = $this->productRepository->find($command->productId());
        $item = new OrderItem($order, $product, $command->quantity());

        $order->items()->add($item);

        $this->orderRepository->save($order);
    }
}
