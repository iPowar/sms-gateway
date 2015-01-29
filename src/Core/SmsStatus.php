<?php

namespace SmsGateway\Core;

use Exception;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class SmsStatus 
{
    const _NEW = 'New';
    const _NEW_CODE = 1;

    const ACCEPTED = 'Accepted';
    const ACCEPTED_CODE = 2;

    const NOT_FOUND = 'Not Found';
    const NOT_FOUND_CODE = 3;

    const NOT_DELIVERED = 'Not Delivered';
    const NOT_DELIVERED_CODE = 4;

    const SENT = 'Sent';
    const SENT_CODE = 5;

    const DELIVERED = 'Delivered';
    const DELIVERED_CODE = 6;

    const SCHEDULED = 'Scheduled';
    const SCHEDULED_CODE = 7;

    const ERROR = 'Error';
    const ERROR_CODE = 8;

    /**
     * @return array
     */
    public static function getSmsStatuses()
    {
        return array(
            self::_NEW_CODE => self::_NEW,
            self::ACCEPTED_CODE => self::ACCEPTED,
            self::NOT_FOUND_CODE => self::NOT_FOUND,
            self::NOT_DELIVERED_CODE => self::NOT_DELIVERED,
            self::SENT_CODE => self::SENT,
            self::DELIVERED_CODE => self::DELIVERED,
            self::SCHEDULED_CODE => self::SCHEDULED,
            self::ERROR_CODE => self::ERROR
        );
    }

    /**
     * @param int $code
     * @return string
     * @throws Exception
     */
    public static function getStatusByCode($code)
    {
        if (!array_key_exists($code, static::getSmsStatuses())) {
            throw new Exception(sprintf('Code "%s" was not found in "%s" class.', $code, get_called_class()));
        }

        return static::getSmsStatuses()[$code];
    }

    /**
     * @param string $status
     * @return int
     * @throws Exception
     */
    public static function getCodeByStatus($status)
    {
        if (!$code = array_search($status, static::getSmsStatuses())) {
            throw new Exception(sprintf('Status "%s" was not found in "%s" class.', $status, get_called_class()));
        }

        return $code;
    }
}