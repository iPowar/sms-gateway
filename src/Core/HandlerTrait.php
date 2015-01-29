<?php

namespace SmsGateway\Core;

use SmsGateway\Validate\PhoneNumberValidator;
use SmsGateway\Fabric\SmsCGateway;
use SmsGateway\Fabric\SmsPilotGateway;
use SmsGateway\Exception\SmsGatewayException;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
trait HandlerTrait
{
    /**
     * @throws SmsGatewayException
     */
    private function checkConfig()
    {
        if (!is_array($this->config) || !array_key_exists('gateway_collection', $this->config)) {
            throw SmsGatewayException::unAvailableGateway();
        }
    }

    /**
     * @param string $gatewayName
     * @return AbstractSmsGateway
     * @TODO need refactoring
     */
    private function createGatewayByGatewayName($gatewayName)
    {
        $gatewayConfig = $this->config['gateway_collection'][$gatewayName];

        switch ($gatewayName) {
            default:
            case 'sms_c':
                $gateway = new SmsCGateway($gatewayConfig);
                break;
            case 'sms_pilot':
                $gateway = new SmsPilotGateway($gatewayConfig);
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