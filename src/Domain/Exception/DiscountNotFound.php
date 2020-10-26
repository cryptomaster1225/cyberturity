<?php

declare(strict_types=1);

namespace Domain\Exception;

/**
 * Class DiscountNotFound
 * @package Domain\Exception
 */
final class DiscountNotFound extends \InvalidArgumentException
{
    public static function withId(string $id): DiscountNotFound
    {
        return new self(\sprintf('Discount %s with id not found.', $id));
    }

    public static function withCode(string $code): DiscountNotFound
    {
        return new self(\sprintf('Discount %s with code not found.', $code));
    }
}
