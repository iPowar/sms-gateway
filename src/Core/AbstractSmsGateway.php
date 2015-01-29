<?php

namespace SmsGateway\Core;

use SmsGateway\Exception\SmsGatewayException;
use SmsGateway\Message\Message;
use SmsGateway\Validate\ConfigValidator;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
abstract class AbstractSmsGateway
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $host;

    /**
     * @var Message
     */
    private $message;

    /**
     * @param $response
     * @return bool
     */
    abstract protected function hasError($response);

    /**
     * @return string
     */
    abstract protected function getSendUrl();

    /**
     * @return array
     */
    abstract protected function getSendData();

    /**
     * @return string
     */
    abstract protected function getStatusUrl();

    /**
     * @return array
     */
    abstract protected function getStatusData();

    /**
     * @return mixed
     */
    abstract protected function getSmsIdFromResponse($curlResponse);

    /**
     * @param $curlResponse
     * @return mixed
     */
    abstract protected function getSmStatusFromResponse($curlResponse);

    /**
     * @param array $config
     * @throws SmsGatewayException
     */
    public function __construct(array $config)
    {
        $configValidator = $this->getConfigValidator()->validate($config);
        if (!$configValidator->isValid()) {
            throw SmsGatewayException::incorrectСonfiguration($configValidator->getError());
        }

        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->host = $config['host'];
    }

    /**
     * @return Response
     */
    public function send()
    {
        $response = new Response();

        try {
            $curlResponse = $this->createRequest($this->getSendUrl(), $this->getSendData());

            $response->setId($this->getSmsIdFromResponse($curlResponse));
            $response->setPhone($this->getMessage()->getPhoneNumber());
            $response->setStatus(SmsStatus::ACCEPTED_CODE);
        } catch (SmsGatewayException $e) {
            $response->setStatus(SmsStatus::ERROR_CODE);
            $response->setError($e->getMessage());
        }

        return $response;
    }

    /**
     * @return Response
     */
    public function getSmsStatus()
    {
        $response = new Response();

        try {
            $curlResponse = $this->createRequest($this->getStatusUrl(), $this->getStatusData());

            $response->setStatus($this->getSmStatusFromResponse($curlResponse));
        } catch (SmsGatewayException $e) {
            $response->setStatus(SmsStatus::ERROR_CODE);
            $response->setError($e->getMessage());
        }

        return $response;
    }

    /**
     * @return bool
     */
    public function isGatewayAvailable()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param string $url
     * @param string|array $data
     * @return string
     * @throws SmsGatewayException
     */
    private function createRequest($url, $data)
    {
        if (!$this->isGatewayAvailable()) {
            throw SmsGatewayException::unAvailableGateway();
        }

        $request = new Request();
        $request->createPostRequest($url, $data);

        $curlResponse = json_decode($request->getResponse());

        if ($this->hasError($curlResponse)) {
            throw SmsGatewayException::unAvailableGateway();
        }

        return $curlResponse;
    }

    /**
     * @return ConfigValidator
     */
    private function getConfigValidator()
    {
        return new ConfigValidator();
    }
}
