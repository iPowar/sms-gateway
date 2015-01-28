<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;
use SmsGateway\Core\Response;
use SmsGateway\Exception\SmsGatewayException;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsPilotGateway extends AbstractSmsGateway
{
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

        if ((int)$response->send[0]->status < 0) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return 'https://' . $this->getHost() . '/api2.php';
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $array = array(
            'login' => $this->getUser(),
            'password' => $this->getPassword(),
            'send' => array(
                array(
                    'from' => $this->sender,
                    'to' => $this->getMessage()->getPhoneNumber(),
                    'text' => $this->getMessage()->getContent(),
                    )
            ),
        );

        $json = json_encode($array);

        return array('json' => $json);
    }

    /**
     * @param $curlResponse
     * @return mixed
     */
    protected function getSmsIdFromResponse($curlResponse)
    {
        return $curlResponse->send[0]->server_id;
    }
}