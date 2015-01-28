<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;
use SmsGateway\Core\Response;
use SmsGateway\Exception\SmsGatewayException;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsCGateway extends AbstractSmsGateway
{
    /**
     * @var string
     */
    private $sender;

    /**
     * @var int
     */
    private $format;

    /**
     * @var string
     */
    private $valid;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
        $this->format = $config['format'];
        $this->valid = $config['valid'];
    }

    /**
     * @param $response
     * @return bool
     */
    protected function hasError($response)
    {
        if ($response->error_code) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return 'https://' . $this->getHost() . '/sys/send.php';
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return array(
            'login' => $this->getUser(),
            'psw' => md5($this->getPassword()),
            'phones' => $this->getMessage()->getPhoneNumber(),
            'mes' => $this->getMessage()->getContent(),
            'sender' => $this->sender,
            'fmt' => $this->format,
            'valid' => $this->valid,
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