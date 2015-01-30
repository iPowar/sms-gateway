<?php

namespace SmsGateway\Core;

use Exception;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsStatus 
{
    const NEW_CODE = 1;
    const ACCEPTED_CODE = 2;
    const NOT_FOUND_CODE = 3;
    const NOT_DELIVERED_CODE = 4;
    const SENT_CODE = 5;
    const DELIVERED_CODE = 6;
    const SCHEDULED_CODE = 7;
    const ERROR_CODE = 8;

    /**
     * @return array
     */
    public static function getSmsStatuses()
    {
        return array(
            self::NEW_CODE,
            self::ACCEPTED_CODE,
            self::NOT_FOUND_CODE,
            self::NOT_DELIVERED_CODE,
            self::SENT_CODE,
            self::DELIVERED_CODE,
            self::SCHEDULED_CODE,
            self::ERROR_CODE,
        );
    }

    /**
     * @param int $code
     * @return string
     * @throws Exception
     */
    public static function isAvailable($code)
    {
        if (!in_array($code, static::getSmsStatuses())) {
            return false;
        }

        return true;
    }
}