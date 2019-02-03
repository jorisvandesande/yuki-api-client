<?php
declare(strict_types=1);

namespace JVDS\YukiClient\Exception;

use Throwable;

class ServerException extends \RuntimeException implements Exception
{
    public static function fromThrowable(Throwable $throwable): self
    {
        return new self('An unexpected error occurred:' . $throwable->getMessage(), $throwable->getCode(), $throwable);
    }
}
