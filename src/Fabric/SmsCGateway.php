<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;
use SmsGateway\Core\SmsStatus;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsCGateway extends AbstractSmsGateway
{
    const NOT_FOUND_STATUS_CODE = -3;
    const NOT_DELIVERED_STATUS_CODE = 20;
    const ACCEPTED_STATUS_CODE = 0;
    const DELIVERED_STATUS_CODE = 1;
    const SCHEDULED_STATUS_CODE = -1;

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
        if (isset($response->error_code)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getSendUrl()
    {
        return 'https://' . $this->getHost() . '/sys/send.php';
    }

    /**
     * @return array
     */
    protected function getSendData()
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
     * @return string
     */
    protected function getStatusUrl()
    {
        return 'https://' . $this->getHost() . '/sys/status.php';
    }

    /**
     * @return array
     */
    protected function getStatusData()
    {
        return array(
            'login' => $this->getUser(),
            'psw' => md5($this->getPassword()),
            'phone' => $this->getMessage()->getPhoneNumber(),
            'id' => $this->getMessage()->getGatewaySmsId(),
            'fmt' => $this->format,
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

    /**
     * @param $curlResponse
     * @return int
     */
    protected function getSmStatusFromResponse($curlResponse)
    {
        $gatewayStatus = $curlResponse->status;

        switch ($gatewayStatus) {
            case self::ACCEPTED_STATUS_CODE:
                $status = SmsStatus::SENT_CODE;
                break;
            case self::DELIVERED_STATUS_CODE:
                $status = SmsStatus::DELIVERED_CODE;
                break;
            case self::NOT_DELIVERED_STATUS_CODE:
                $status = SmsStatus::NOT_DELIVERED_CODE;
                break;
            case self::NOT_FOUND_STATUS_CODE:
                $status = SmsStatus::NOT_FOUND_CODE;
                break;
            case self::SCHEDULED_STATUS_CODE:
                $status = SmsStatus::SCHEDULED_CODE;
                break;
            default:
                $status = SmsStatus::ERROR_CODE;
        }

        return $status;
    }
}