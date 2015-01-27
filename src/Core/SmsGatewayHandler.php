<?php

namespace SmsGateway\Core;

use SmsGateway\Exception\SmsGatewayException;
use SmsGateway\Fabric\SmsAeroGateway;
use SmsGateway\Fabric\SmsCGateway;
use SmsGateway\Fabric\SmsGreenGateway;
use SmsGateway\Message\Message;
use SmsGateway\Validate\PhoneNumberValidator;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsGatewayHandler 
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     * @TODO refactoring set config
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
        if (!is_array($this->config) || !array_key_exists('gateway_collection', $this->config)) {
            throw SmsGatewayException::unAvailableGateway();
        }

        $phoneNumberValidator = $this->getPhoneNumberValidator()->validate($message->getPhoneNumber());
        if (!$phoneNumberValidator->isValid()) {
            throw SmsGatewayException::incorrectPhoneNumber();
        }

        foreach ($this->config['gateway_collection'] as $gatewayName => $gatewayConfig) {
            if (!array_key_exists('enabled', $gatewayConfig) || !$gatewayConfig['enabled']) {
                continue;
            }

            $gateway = $this->createGatewayByGatewayName($gatewayName, $gatewayConfig);
            $gateway->setMessage($message);

            $gatewayResponse = $gateway->send();
            $gatewayResponse->setGatewayId($gatewayName);

            if ($gatewayResponse->getStatus() === Response::ACCEPTED) {
                return $gatewayResponse;
            }
        }

        throw SmsGatewayException::unAvailableGateway();
    }

    /**
     * @param $phoneNumber
     */
    public function handleRequestStatus($phoneNumber)
    {
        //@TODO must implements after implement phone storage
    }

    /**
     * @param string $gatewayName
     * @param array $gatewayConfig
     * @return AbstractSmsGateway
     * @TODO need refactoring
     */
    private function createGatewayByGatewayName($gatewayName, array $gatewayConfig)
    {
        switch ($gatewayName) {
            default:
            case 'sms_aero':
                $gateway = new SmsAeroGateway($gatewayConfig);
                break;
            case 'sms_c':
                $gateway = new SmsCGateway($gatewayConfig);
                break;
            case 'sms_green':
                $gateway = new SmsGreenGateway($gatewayConfig);
                break;
        }

        return $gateway;
    }

    /**
     * @return PhoneNumberValidator
     */
    private function getPhoneNumberValidator()
    {
        return new PhoneNumberValidator();
    }
}