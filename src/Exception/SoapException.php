<?php
declare(strict_types=1);

namespace JVDS\YukiClient\Exception;

use SoapFault;

class SoapException extends \RuntimeException implements Exception
{
    public function __construct(SoapFault $soapFault, string $message = null, int $code = 0)
    {
        parent::__construct($message ?? $soapFault->getMessage(), $code, $soapFault);
    }

    public function getSoapFault(): SoapFault
    {
        return $this->getPrevious();
    }

    public static function fromSoapFault(SoapFault $soapFault): self
    {
        return new self($soapFault);
    }
}
