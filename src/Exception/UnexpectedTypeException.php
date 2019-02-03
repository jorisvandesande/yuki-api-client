<?php
declare(strict_types=1);

namespace JVDS\YukiClient\Exception;

use RuntimeException;

class UnexpectedTypeException extends RuntimeException implements Exception
{
    public static function fromValue($value, string $expectedType): self
    {
        return new self(sprintf(
            'Expected argument of type %s, %s given',
            $expectedType,
            \is_object($value) ? \get_class($value) : \gettype($value))
        );
    }
}
