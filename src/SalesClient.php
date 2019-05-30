<?php
declare(strict_types=1);

namespace JVDS\YukiClient;

use JVDS\YukiClient\Exception\Exception;
use JVDS\YukiClient\Exception\UnexpectedTypeException;
use JVDS\YukiClient\Soap\ClientFactory;
use SimpleXMLElement;

class SalesClient extends Client
{
    private const WSDL = 'https://api.yukiworks.nl/ws/Sales.asmx?wsdl';

    protected const SESSION_ID_ARG = 'sessionId';

    public function __construct(
        string $accessKey,
        array $soapClientOptions = [],
        ClientFactory $factory = null,
        string $wsdl = self::WSDL
    ) {
        parent::__construct($accessKey, $wsdl, $soapClientOptions, $factory);
    }

    /**
     * @param string $administrationId
     * @param string $xmlDoc
     * @return SimpleXMLElement
     * @throws Exception
     * @throws UnexpectedTypeException
     *
     * @link http://help.yuki.nl/documents/homepage/startpagina-yuki-api-documentatie/sales-webservice/sales---functionele-beschrijving-verkoopfacturen-xml
     */
    public function processSalesInvoices(
        string $administrationId,
        string $xmlDoc
    ): SimpleXMLElement {
        $arguments = compact('administrationId');
        $arguments['xmlDoc'] = ['any' => $xmlDoc];

        $response = $this->call('ProcessSalesInvoices', $arguments);

        $xmlResponse = $response->ProcessSalesInvoicesResult->any ?? null;

        if (is_string($xmlResponse)) {
            return new SimpleXMLElement($xmlResponse);
        }

        UnexpectedTypeException::fromValue($xmlResponse, 'string');
    }
}
