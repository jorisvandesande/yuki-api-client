<?php
declare(strict_types=1);

namespace JVDS\YukiClient\Exception;

class InvalidAccessKeyException extends SoapException
{
    public static function create(\SoapFault $soapFault): self
    {
        return new self($soapFault, 'The supplied access key is invalid, please verify that your Yuki access key is correct.');
    }
}
