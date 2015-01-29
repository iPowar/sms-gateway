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
     * @var string
     */
    private $gatewaySmsId;

    /**
     * @var string
     */
    private $gatewayLabel;

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

        if ($generateCode) {
            $this->code = $this->generateCode();
            $this->content = $this->generateContent();
        }
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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