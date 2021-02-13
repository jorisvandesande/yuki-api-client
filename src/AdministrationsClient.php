<?php
declare(strict_types=1);

namespace JVDS\YukiClient;

use JVDS\YukiClient\Exception\Exception;
use JVDS\YukiClient\Exception\UnexpectedTypeException;
use JVDS\YukiClient\Soap\ClientFactory;
use SimpleXMLElement;

class AdministrationsClient extends Client
{
    private const WSDL = 'https://api.yukiworks.nl/ws/Accounting.asmx?wsdl';

    public function __construct(
        string $accessKey,
        array $soapClientOptions = [],
        ClientFactory $factory = null,
        string $wsdl = self::WSDL
    ) {
        parent::__construct($accessKey, $wsdl, $soapClientOptions, $factory);
    }


    public function administrations(
    ): SimpleXMLElement {
        $response = $this->call('Administrations', []);

        $xmlResponse = $response->AdministrationsResult->any ?? null;

        if (is_string($xmlResponse)) {
            return new SimpleXMLElement($xmlResponse);
        }

        throw UnexpectedTypeException::fromValue($xmlResponse, 'string');
    }
}
