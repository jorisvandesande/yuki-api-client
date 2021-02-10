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
        $administrationID,
        $GLAccountCode = "",
        $StartDate = '2020-01-01',
        $EndDate = '2021-01-01',
        $financialMode = 1
    ):array {
        $arguments = compact("administrationID", "GLAccountCode", "StartDate", "EndDate", "financialMode");

        $response = $this->call('GetTransactionDetails', $arguments);
        $arrayResponse = $response->GetTransactionDetailsResult->TransactionInfo ?? null;

        if (is_array($arrayResponse)) {
            return $arrayResponse;
        }

        UnexpectedTypeException::fromValue($arrayResponse, 'array');
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
        $administrationID,
        $bookyear = "2020",
        $financialMode = 1
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
