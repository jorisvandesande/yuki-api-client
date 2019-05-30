<?php
declare(strict_types=1);

namespace JVDS\YukiClient;

use JVDS\YukiClient\Exception\InvalidAccessKeyException;
use JVDS\YukiClient\Exception\ServerException;
use JVDS\YukiClient\Exception\SoapException;
use JVDS\YukiClient\Soap\ClientFactory;

use JVDS\YukiClient\Soap\NativeClientFactory;
use SoapClient;
use SoapFault;
use Throwable;

abstract class Client
{
    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var \SoapClient
     */
    private $soapClient;

    private const SOAP_OPTIONS = [
        'exceptions' => true
    ];

    protected const SESSION_ID_ARG = 'sessionID';

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $wsdl;

    /**
     * @var array
     */
    private $soapOptions;

    /**
     * @var ClientFactory|NativeClientFactory
     */
    private $clientFactory;

    protected function __construct(string $accessKey, string $wsdl, array $soapOptions = [], ClientFactory $clientFactory = null)
    {
        $this->accessKey = $accessKey;
        $this->wsdl = $wsdl;
        $this->soapOptions = $soapOptions;
        $this->clientFactory = $clientFactory ?? new NativeClientFactory();
    }

    /**
     * @throws SoapException on soap error
     * @throws ServerException on server failure
     * @throws InvalidAccessKeyException if the access key is invalid
     */
    protected function authenticate(): void
    {
        try {
            $response = $this->soapClient()->__soapCall('Authenticate', [[
                'accessKey' => $this->accessKey
            ]]);

        } catch (SoapFault $soapFault) {
            if (stripos($soapFault->getMessage(), 'Invalid access key') !== false) {
                throw InvalidAccessKeyException::create($soapFault);
            }

            throw SoapException::fromSoapFault($soapFault);

        } catch (Throwable $e) {
            throw ServerException::fromThrowable($e);
        }

        $this->sessionId = $response->AuthenticateResult;
    }

    /**
     * @param string $functionName
     * @param array $arguments
     * @param array $options
     * @param array $inputHeaders
     * @param array $outputHeaders
     * @return mixed
     *
     * @throws SoapException on soap error
     * @throws ServerException on server failure
     * @throws InvalidAccessKeyException if the access key is invalid
     * @see https://secure.php.net/manual/en/soapclient.soapcall.php
     */
    protected function call(string $functionName, array $arguments, array $options = [], array $inputHeaders = [], array & $outputHeaders = [])
    {
        if (!is_string($this->sessionId)) {
            $this->authenticate();
        }

        $arguments += [static::SESSION_ID_ARG => $this->sessionId];

        try {
            return $this->soapClient()->__soapCall($functionName, [$arguments], $options, $inputHeaders, $outputHeaders);
        } catch (SoapFault $soapFault) {
            throw SoapException::fromSoapFault($soapFault);
        }
    }

    /**
     * @return SoapClient
     *
     * @throws SoapFault
     */
    private function soapClient(): SoapClient
    {
        return $this->soapClient
            ?? $this->soapClient = $this->clientFactory->create($this->wsdl, self::SOAP_OPTIONS + $this->soapOptions);
    }
}
