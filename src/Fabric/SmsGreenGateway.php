<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsGreenGateway extends AbstractSmsGateway
{
    /**
     * @var int
     */
    private $dlr;

    /**
     * @var string
     */
    private $sender;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
        $this->dlr = $config['dlr'];
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
        return 'https://' . $this->getHost() . '/mt.cgi';
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return array(
            'user' => $this->getUser(),
            'pass' => $this->getPassword(),
            'to' => $this->getMessage()->getPhoneNumber(),
            'txt' => $this->getMessage()->getContent(),
            'from' => $this->sender,
            'Dlr' => $this->dlr,
        );
    }
}