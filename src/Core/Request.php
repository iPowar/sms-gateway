<?php

namespace SmsGateway\Core;

/**
 * @author Mikhail Kudryashov <kudryashov@fortfs.com>
 */
class Request 
{
    /**
     * @var string
     */
    private $response;

    /**
     * @param string $url
     * @param array $postFields
     */
    public function createPostRequest($url, array $postFields)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postFields
        ));

        $this->response = curl_exec($curl);

        curl_close($curl);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}