<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;
use SmsGateway\Core\SmsStatus;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsPilotGateway extends AbstractSmsGateway
{
    const NOT_FOUND_STATUS_CODE = -2;
    const NOT_DELIVERED_STATUS_CODE = -1;
    const ACCEPTED_STATUS_CODE = 0;
    const SENT_STATUS_CODE = 1;
    const DELIVERED_STATUS_CODE = 2;
    const SCHEDULED_STATUS_CODE = 3;

    /**
     * @var string
     */
    private  $sender;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
    }

    /**
     * @param $response
     * @return bool
     */
    protected function hasError($response)
    {
        if (isset($response->error)) {
            return true;
        }

        if (isset($response->send) && (int)$response->send[0]->status < 0) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getSendUrl()
    {
        return 'https://' . $this->getHost() . '/api2.php';
    }

    /**
     * @return string
     */
    protected function getStatusUrl()
    {
        return $this->getSendUrl();
    }

    /**
     * @return array
     */
    protected function getSendData()
    {
        return array(
            'json' => json_encode(array(
                'login' => $this->getUser(),
                'password' => $this->getPassword(),
                'send' => array(
                    array(
                        'from' => $this->sender,
                        'to' => $this->getMessage()->getPhoneNumber(),
                        'text' => $this->getMessage()->getContent(),
                    )
                )
            ))
        );
    }

    /**
     * @return array
     */
    protected function getStatusData()
    {
        return array(
            'json' => json_encode(array(
                'login' => $this->getUser(),
                'password' => $this->getPassword(),
                'check' => array(
                    array(
                        'server_id' => $this->getGatewaySmsId(),
                    )
                )
            ))
        );
    }

    /**
     * @param $curlResponse
     * @return mixed
     */
    protected function getSmsIdFromResponse($curlResponse)
    {
        return $curlResponse->send[0]->server_id;
    }

    /**
     * @param $curlResponse
     * @return int
     */
    protected function getSmStatusFromResponse($curlResponse)
    {
        $gatewayStatus = $curlResponse->check[0]->status;

        switch ($gatewayStatus) {
            case self::ACCEPTED_STATUS_CODE:
                $status = SmsStatus::ACCEPTED_CODE;
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
            case self::SENT_STATUS_CODE:
                $status = SmsStatus::SENT_CODE;
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