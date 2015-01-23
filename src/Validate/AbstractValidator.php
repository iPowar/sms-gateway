<?php

namespace SmsGateway\Validate;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
abstract class AbstractValidator
{
    /**
     * @var array
     */
    protected $error = array();

    /**
     * @param mixed $object
     * @return AbstractValidator
     */
    abstract public function validate($object);

    /**
     * @return bool
     */
    public function isValid()
    {
        if (is_array($this->error) && count($this->error) > 0) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
}