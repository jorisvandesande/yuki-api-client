<?php
declare(strict_types=1);

namespace JVDS\YukiClient;

use JVDS\YukiClient\Exception\Exception;
use JVDS\YukiClient\Exception\UnexpectedTypeException;
use JVDS\YukiClient\Soap\ClientFactory;
use SimpleXMLElement;

class ArchiveClient extends Client
{
    private const WSDL = 'https://api.yukiworks.nl/ws/Archive.asmx?wsdl';

    public function __construct(
        string $accessKey,
        array $soapClientOptions = [],
        ClientFactory $factory = null,
        string $wsdl = self::WSDL
    ) {
        parent::__construct($accessKey, $wsdl, $soapClientOptions, $factory);
    }

    /**
     * @param int $folderID
     * @param int $startRecord
     * @param int $numberOfRecords
     * @param string $sortOrder CreatedDesc or CreatedAsc or ModifiedDesc or ModifiedAsc or DocumentDateDesc or DocumentDateAsc or ContactNameAsc or ContactNameDesc
     * @param string $startDate date in Y-m-d\TH:i:s format, or 0001-01-01 to ignore the date
     * @param string $endDate date in Y-m-d\TH:i:s format, or 0001-01-01 to ignore the date
     * @return SimpleXMLElement[]
     * @throws Exception
     * @throws UnexpectedTypeException
     *
     * @link https://support.yuki.nl/nl/support/solutions/articles/11000062998-archive-webservice
     * @Link https://api.yukiworks.nl/ws/archive.asmx?op=DocumentsInFolder
     */
    public function documentsInFolder(
        int $folderID,
        int $startRecord = 1,
        int $numberOfRecords = 100,
        string $sortOrder = 'ModifiedDesc',
        string $startDate = '0001-01-01',
        string $endDate = '0001-01-01'
    ): array {
        $arguments = compact('folderID', 'startRecord', 'numberOfRecords', 'sortOrder', 'startDate', 'endDate');

        $response = $this->call('DocumentsInFolder', $arguments);

        $xmlResponse = $response->DocumentsInFolderResult->any ?? null;

        if (is_string($xmlResponse)) {
            return iterator_to_array((new SimpleXMLElement($xmlResponse)), false);
        }

        throw UnexpectedTypeException::fromValue($xmlResponse, 'string');
    }

    /**
     * @param string $searchText
     * @param string $searchOption All or Creator or Contact or Subject or Tag or Type
     * @param int $folderID
     * @param int $startRecord
     * @param int $numberOfRecords
     * @param string $sortOrder CreatedDesc or CreatedAsc or ModifiedDesc or ModifiedAsc or DocumentDateDesc or DocumentDateAsc or ContactNameAsc or ContactNameDesc
     * @param string $startDate date in Y-m-d\TH:i:s format, or 0001-01-01 to ignore the date
     * @param string $endDate date in Y-m-d\TH:i:s format, or 0001-01-01 to ignore the date
     * @return SimpleXMLElement[]
     * @throws Exception
     * @throws UnexpectedTypeException
     *
     * @link https://support.yuki.nl/nl/support/solutions/articles/11000062998-archive-webservice
     * @Link https://api.yukiworks.nl/ws/archive.asmx?op=SearchDocuments
     */
    public function searchDocuments(
        string $searchText = '',
        string $searchOption = 'All',
        int $folderID = -1,
        int $startRecord = 1,
        int $numberOfRecords = 100,
        string $sortOrder = 'ModifiedDesc',
        string $startDate = '0001-01-01',
        string $endDate = '0001-01-01'
    ): array {
        $arguments = compact('searchText', 'searchOption', 'folderID', 'startRecord', 'numberOfRecords', 'sortOrder', 'startDate', 'endDate');

        $response = $this->call('SearchDocuments', $arguments);

        $xmlResponse = $response->SearchDocumentsResult->any ?? null;

        if (is_string($xmlResponse)) {
            return iterator_to_array((new SimpleXMLElement($xmlResponse)), false);
        }

        throw UnexpectedTypeException::fromValue($xmlResponse, 'string');
    }

    /**
     * @param string $documentID
     * @return SimpleXMLElement
     *
     * @link https://support.yuki.nl/nl/support/solutions/articles/11000062998-archive-webservice
     * @Link https://api.yukiworks.nl/ws/archive.asmx?op=DocumentBinaryData
     */
    public function documentBinaryData(string $documentID): string
    {
        $arguments = compact('documentID');

        $response = $this->call('DocumentBinaryData', $arguments);

        $data = $response->DocumentBinaryDataResult ?? null;

        if (is_string($data)) {
            return $data;
        }

        throw UnexpectedTypeException::fromValue($data, 'string');
    }
}
