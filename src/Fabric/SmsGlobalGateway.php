<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsGlobalGateway extends AbstractSmsGateway
{
    /**
     * @var string
     */
    private  $sender;

    /**
     * @var string
     */
    private $action;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
        $this->action = 'sendsms';
    }

    /**
     * @param $response
     * @return bool
     */
    protected function hasError($response)
    {
        // TODO: Implement hasError() method.
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return 'http://' . $this->getHost() . '/http-api.php';
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return array(
            'action' => $this->action,
            'user' => $this->getUser(),
            'password' => $this->getPassword(),
            'to' => $this->getMessage()->getPhoneNumber(),
            #'from' => $this->sender,
            'txt' => $this->getMessage()->getContent(),
        );
    }
}