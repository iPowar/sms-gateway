<?php

namespace SmsGateway\Message;

use DateTime;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class Message
{
    /**
     * @var string
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
    private $gatewaySmsId;

    /**
     * @var string
     */
    private $gatewayLabel;

    /**
     * @param string $phoneNumber
     * @param string $content
     */
    public function __construct($phoneNumber, $content)
    {
        $this->phoneNumber = $phoneNumber;
        $this->content = $content;
        $this->createdAt = new DateTime();
    }

    /**
     * @return string
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
     * @return mixed
     */
    public function getGatewaySmsId()
    {
        return $this->gatewaySmsId;
    }

    /**
     * @param mixed $gatewaySmsId
     */
    public function setGatewaySmsId($gatewaySmsId)
    {
        $this->gatewaySmsId = $gatewaySmsId;
    }

    /**
     * @return string
     */
    public function getGatewayLabel()
    {
        return $this->gatewayLabel;
    }

    /**
     * @param string $gatewayLabel
     */
    public function setGatewayLabel($gatewayLabel)
    {
        $this->gatewayLabel = $gatewayLabel;
    }
}