<?php
declare(strict_types=1);

namespace JVDS\YukiClient\Soap;

use SoapClient;

final class NativeClientFactory implements ClientFactory
{
    public function create(string $wsdl, array $options = []): SoapClient
    {
        return new SoapClient($wsdl, $options);
    }

}
