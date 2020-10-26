<?php

declare(strict_types=1);

namespace Domain\Exception;

/**
 * Class ProductNotFound
 * @package Domain\Exception
 */
final class ProductNotFound extends \InvalidArgumentException
{
    public static function withId(string $id): ProductNotFound
    {
        return new self(\sprintf('Product %s with id not found.', $id));
    }
}
