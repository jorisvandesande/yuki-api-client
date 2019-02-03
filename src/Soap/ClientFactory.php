<?php
declare(strict_types=1);

namespace JVDS\YukiClient\Soap;

use SoapClient;

interface ClientFactory
{
    public function create(string $wsdl, array $options = []): SoapClient;
}
