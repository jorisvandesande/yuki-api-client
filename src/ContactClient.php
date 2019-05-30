<?php
declare(strict_types=1);

namespace JVDS\YukiClient;

use JVDS\YukiClient\Exception\Exception;
use JVDS\YukiClient\Exception\UnexpectedTypeException;
use JVDS\YukiClient\Soap\ClientFactory;
use SimpleXMLElement;

class ContactClient extends Client
{
    private const WSDL = 'https://api.yukiworks.nl/ws/Contact.asmx?wsdl';

    public function __construct(
        string $accessKey,
        array $soapClientOptions = [],
        ClientFactory $factory = null,
        string $wsdl = self::WSDL
    ) {
        parent::__construct($accessKey, $wsdl, $soapClientOptions, $factory);
    }

    /**
     * @param int $pageNumber
     * @param string $searchOption
     * @param string|null $searchValue
     * @param string|null $domainId
     * @param string $sortOrder
     * @param string $modifiedAfter
     * @param string $active
     * @return SimpleXMLElement[]
     * @throws Exception
     * @throws UnexpectedTypeException
     */
    public function searchContacts(
        int $pageNumber = 1,
        string $searchOption = 'All',
        string $searchValue = null,
        string $domainId = null,
        string $sortOrder = 'ModifiedDesc',
        string $modifiedAfter = '2000-01-01',
        string $active = 'Both'
    ): array {
        $arguments = compact('searchOption', 'searchValue', 'domainId', 'sortOrder', 'modifiedAfter', 'active', 'pageNumber');

        $response = $this->call('SearchContacts', $arguments);

        $xmlResponse = $response->SearchContactsResult->any ?? null;

        if (is_string($xmlResponse)) {
            return iterator_to_array((new SimpleXMLElement($xmlResponse)), false);
        }

        UnexpectedTypeException::fromValue($xmlResponse, 'string');
    }
}
