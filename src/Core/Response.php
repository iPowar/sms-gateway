<?php

namespace SmsGateway\Core;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class Response 
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $phone;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $gatewayId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param int $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getGatewayId()
    {
        return $this->gatewayId;
    }

    /**
     * @param string $gatewayId
     */
    public function setGatewayId($gatewayId)
    {
        $this->gatewayId = $gatewayId;
    }
}
