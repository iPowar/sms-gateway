<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;
use SmsGateway\Core\Response;
use SmsGateway\Exception\SmsGatewayException;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsAeroGateway extends AbstractSmsGateway
{
    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $format;

    /**
     * @param array $config
     *
     * @TODO refactoring set config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
        $this->format = $config['format'];
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

        if ($curlResponse->result != 'accepted') {
            throw SmsGatewayException::unableToSend($curlResponse->reason);
        }

        $response->setId($curlResponse->id);
        $response->setPhone($this->getMessage()->getPhoneNumber());
        $response->setStatus(Response::ACCEPTED);

        return $response;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return 'https://' . $this->getHost() . '/send';
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return array(
            'user' => $this->getUser(),
            'password' => md5($this->getPassword()),
            'to' => $this->getMessage()->getPhoneNumber(),
            'text' => $this->getMessage()->getContent(),
            'from' => $this->sender,
            'answer' => $this->format,
        );
    }
}
