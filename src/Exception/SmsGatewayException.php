<?php

namespace SmsGateway\Exception;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsGatewayException extends \Exception
{
    /**
     * @return SmsGatewayException
     */
    public static function unAvailableGateway()
    {
        return new self('service unavailable');
    }

    /**
     * @param string $message
     * @return SmsGatewayException
     */
    public static function unableToSend($message)
    {
        return new self($message);
    }

    /**
     * @param array $error
     * @return SmsGatewayException
     */
    public static function incorrectСonfiguration(array $error = array())
    {
        return new self('incorrect configuration: ' . implode(', ', $error));
    }

    /**
     * @return SmsGatewayException
     */
    public static function incorrectPhoneNumber()
    {
        return new self('incorrect phone number');
    }
}