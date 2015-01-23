<?php

namespace SmsGateway\Core;

use DateTime;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class Message
{
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
     * @param int $phoneNumber
     * @param string $content
     */
    public function __construct($phoneNumber, $content = null)
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
}