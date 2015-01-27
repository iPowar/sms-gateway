<?php

namespace SmsGateway\Message;

use DateTime;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class Message
{
    const MESSAGE = 'Your authorization code: ';
    const CODE_LENGTH = 5;

    /**
     * @var int
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $content;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $code;

    /**
     * @param $phoneNumber
     * @param null $content
     * @param bool $generateCode
     */
    public function __construct($phoneNumber, $content = null, $generateCode = false)
    {
        $this->phoneNumber = $phoneNumber;
        $this->content = $content;
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    private function generateCode()
    {
        return substr(md5(time()), 0, self::CODE_LENGTH);
    }

    /**
     * @return string
     */
    private function generateContent()
    {
        if ($message = $this->getContent()) {
            return $message . $this->code;
        }

        return self::MESSAGE . $this->code;
    }
}