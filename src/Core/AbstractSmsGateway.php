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
    abstract protected function getUrl();

    /**
     * @return array
     */
    abstract protected function getData();

    /**
     * @return mixed
     */
    abstract protected function getSmsIdFromResponse($curlResponse);

    /**
     * @param array $config
     * @throws SmsGatewayException
     *
     * @TODO refactoring set config
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
        try {
            $response = $this->sendRequest();
        } catch (SmsGatewayException $e) {
            $response = new Response();
            $response->setStatus(Response::ERROR);
            $response->setError($e->getMessage());
        }

            return $response;
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
     * @return bool
     */
    public function isGatewayAvailable()
    {
        return true;
    }

    /**
     * @throws SmsGatewayException
     */
    protected function sendRequest()
    {
        if (!$this->isGatewayAvailable()) {
            throw SmsGatewayException::unAvailableGateway();
        }

        $request = new Request();
        $request->createPostRequest($this->getUrl(), $this->getData());

        $response = $this->handleResponse($request->getResponse());

        return $response;
    }

    /**
     * @param string $curlResponse
     * @return Response
     * @throws SmsGatewayException
     */
    protected function handleResponse($curlResponse)
    {
        $response = new Response();
        $curlResponse = json_decode($curlResponse);

        if ($this->hasError($curlResponse)) {
            throw SmsGatewayException::unAvailableGateway();
        }

        $response->setId($this->getSmsIdFromResponse($curlResponse));
        $response->setPhone($this->getMessage()->getPhoneNumber());
        $response->setStatus(Response::ACCEPTED);

        return $response;
    }

    /**
     * @return ConfigValidator
     */
    private function getConfigValidator()
    {
        return new ConfigValidator();
    }
}