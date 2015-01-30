<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;
use SmsGateway\Core\SmsStatus;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsCGateway extends AbstractSmsGateway
{
    const STATUS_NOT_FOUND = -3;
    const STATUS_NOT_DELIVERED = 20;
    const STATUS_ACCEPTED = 0;
    const STATUS_DELIVERED = 1;
    const STATUS_SCHEDULED = -1;

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
        $this->responseType = 'json';
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
            case self::STATUS_ACCEPTED:
                $status = SmsStatus::SENT_CODE;
                break;
            case self::STATUS_DELIVERED:
                $status = SmsStatus::DELIVERED_CODE;
                break;
            case self::STATUS_NOT_DELIVERED:
                $status = SmsStatus::NOT_DELIVERED_CODE;
                break;
            case self::STATUS_NOT_FOUND:
                $status = SmsStatus::NOT_FOUND_CODE;
                break;
            case self::STATUS_SCHEDULED:
                $status = SmsStatus::SCHEDULED_CODE;
                break;
            default:
                $status = SmsStatus::ERROR_CODE;
        }

        return $status;
    }
}