<?php

namespace SmsGateway\Core;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class AuthenticationMessage extends Message
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param int $phoneNumber
     * @param string $content
     */
    public function __construct($phoneNumber, $content = null)
    {
        parent::__construct($phoneNumber, $content);

        $this->code = $this->generateCode();

        $this->setContent($this->generateContent());
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getNewCode()
    {
        return $this->generateCode();
    }

    /**
     * @return string
     */
    private function generateCode()
    {
        return substr(md5(time()), 0, 5);
    }

    /**
     * @return string
     * @TODO need to translation
     */
    private function generateContent()
    {
        return 'your authorization code: ' . $this->code;
    }
}