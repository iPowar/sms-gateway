<?php

namespace SmsGateway\Core;

use SmsGateway\Exception\SmsGatewayException;
use SmsGateway\Message\Message;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsGatewaySendHandler
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

        $phoneNumberValidator = $this->getPhoneNumberValidator()->validate($message->getPhoneNumber());
        if (!$phoneNumberValidator->isValid()) {
            throw SmsGatewayException::incorrectPhoneNumber();
        }

        foreach ($this->config['gateway_collection'] as $gatewayName => $gatewayConfig) {
            if (!array_key_exists('enabled', $gatewayConfig) || !$gatewayConfig['enabled']) {
                continue;
            }

            $gateway = $this->createGatewayByGatewayName($gatewayName);
            $gateway->setMessage($message);

            $gatewayResponse = $gateway->send();
            $gatewayResponse->setGatewayId($gatewayName);

            if ($gatewayResponse->getStatus() === Response::ACCEPTED) {
                return $gatewayResponse;
            }
        }

        throw SmsGatewayException::unAvailableGateway();
    }
}