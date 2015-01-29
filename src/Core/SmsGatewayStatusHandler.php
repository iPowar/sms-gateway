<?php

namespace SmsGateway\Core;

use SmsGateway\Exception\SmsGatewayException;
use SmsGateway\Message\Message;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsGatewayStatusHandler 
{
    use HandlerTrait;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Message $message
     * @return Response
     * @throws SmsGatewayException
     */
    public function handle(Message $message)
    {
        $this->checkConfig();

        $gateway = $this->createGatewayByGatewayName($message->getGatewayLabel());
        $gateway->setMessage($message);

        return $gateway->getSmsStatus();
    }
}