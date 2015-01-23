<?php

namespace SmsGateway\Validate;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class ConfigValidator extends AbstractValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($config)
    {
        if (!is_array($config)) {
            array_push($this->error, 'config must be array');
        }

        if (!array_key_exists('user', $config)) {
            array_push($this->error, 'missing "user" key');
        }

        if (!array_key_exists('password', $config)) {
            array_push($this->error, 'missing "password" key');
        }

        if (!array_key_exists('host', $config)) {
            array_push($this->error, 'missing "host" key');
        }

        return $this;
    }
}