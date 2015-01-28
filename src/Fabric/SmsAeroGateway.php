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
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
        $this->format = $config['format'];
    }

    /**
     * @param $response
     * @return bool
     */
    protected function hasError($response)
    {
        if ($response->result != 'accepted') {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getSendUrl()
    {
        return 'https://' . $this->getHost() . '/send';
    }

    /**
     * @return array
     */
    protected function getSendData()
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

    /**
     * @param $curlResponse
     * @return mixed
     */
    protected function getSmsIdFromResponse($curlResponse)
    {
        return $curlResponse->id;
    }
}
