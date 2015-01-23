<?php

namespace SmsGateway\Validate;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class PhoneNumberValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($phoneNumber)
    {
        if (!is_numeric($phoneNumber)) {
            array_push($this->error, 'incorrect phone number');
        }

        return $this;
    }
}