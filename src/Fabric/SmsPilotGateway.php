<?php

namespace SmsGateway\Fabric;

use SmsGateway\Core\AbstractSmsGateway;

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
     * @var string
     */
    private $key;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->sender = $config['sender'];
        $this->key = $config['key'];
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
        return 'https://' . $this->getHost() . '/api.php';
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return array(
            'key' => $this->key,
            'to' => $this->getMessage()->getPhoneNumber(),
            #'from' => $this->sender,
            'send' => $this->getMessage()->getContent(),
        );
    }
}