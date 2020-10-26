<?php

declare(strict_types=1);

namespace Domain\Exception;

/**
 * Class OrderNotFound
 * @package Domain\Exception
 */
final class OrderNotFound extends \InvalidArgumentException
{
    public static function withId(string $id): OrderNotFound
    {
        return new self(\sprintf('Order %s with id not found.', $id));
    }

    public static function withPaypalId(string $id): OrderNotFound
    {
        return new self(\sprintf('Order %s with paypal id not found.', $id));
    }
}
