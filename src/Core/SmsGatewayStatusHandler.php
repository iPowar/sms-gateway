<?php

namespace SmsGateway\Core;

use SmsGateway\Exception\SmsGatewayException;

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
     * @param $gatewayMessageId
     * @return Response
     * @throws SmsGatewayException
     */
    public function handle($gatewayMessageId, $gatewayName)
    {
        $this->checkConfig();

        $gateway = $this->createGatewayByGatewayName($gatewayName);
        $gateway->setGatewaySmsId($gatewayMessageId);

        return $gateway->getSmsStatus();
    }
}