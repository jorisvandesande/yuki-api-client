<?php
declare(strict_types=1);

namespace JVDS\YukiClient;

use JVDS\YukiClient\Exception\Exception;
use JVDS\YukiClient\Exception\UnexpectedTypeException;
use JVDS\YukiClient\Soap\ClientFactory;
use SimpleXMLElement;

class AccountInfoClient extends Client
{
    private const WSDL = 'https://api.yukiworks.nl/ws/AccountingInfo.asmx?wsdl';

    protected const SESSION_ID_ARG = 'sessionID';

    public function __construct(
        string $accessKey,
        array $soapClientOptions = [],
        ClientFactory $factory = null,
        string $wsdl = self::WSDL
    ) {
        parent::__construct($accessKey, $wsdl, $soapClientOptions, $factory);
    }


    public function getTransactionDetails(
        string $administrationID,
        string $GLAccountCode = "",
        string $StartDate = '2020-01-01',
        string $EndDate = '2021-01-01',
        int $financialMode = 1
    ):array {
        $arguments = compact("administrationID", "GLAccountCode", "StartDate", "EndDate", "financialMode");

        $response = $this->call('GetTransactionDetails', $arguments);
        $arrayResponse = $response->GetTransactionDetailsResult->TransactionInfo ?? null;

        if (is_array($arrayResponse)) {
            return $arrayResponse;
        }

        UnexpectedTypeException::fromValue($arrayResponse, 'array');
    }

    public function getTransactionDocument (
        string $administrationID,
        string $transactionID
    ):object {
        $arguments = compact("administrationID", "transactionID");

        $response = $this->call('GetTransactionDocument', $arguments);
        $documentObject = $response->GetTransactionDocumentResult ?? null;

        if (is_object($documentObject)) {
            return $documentObject;
        }

        UnexpectedTypeException::fromValue($documentObject, 'object');
    }

    public function getGLAccountScheme (
        $administrationID
    ): array {
        $arguments = compact("administrationID");

        $response = $this->call('GetGLAccountScheme', $arguments);
        $arrayResponse = $response->GetGLAccountSchemeResult->GlAccount ?? null;

        if (is_array($arrayResponse)) {
            return $arrayResponse;
        }

        UnexpectedTypeException::fromValue($arrayResponse, 'array');
    }

    public function getStartBalanceByGLAccount(
        string $administrationID,
        string $bookyear = "2020",
        int $financialMode = 1
    ):array {
        $arguments = compact("administrationID", "bookyear", "financialMode");

        $response = $this->call('GetStartBalanceByGLAccount', $arguments);
        $arrayResponse = $response->GetStartBalanceByGlAccountResult->AccountStartBalance ?? null;

        if (is_array($arrayResponse)) {
            return $arrayResponse;
        }

        UnexpectedTypeException::fromValue($arrayResponse, 'array');
    }

}
