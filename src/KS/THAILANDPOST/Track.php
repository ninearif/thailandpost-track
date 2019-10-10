<?php

namespace KS\THAILANDPOST;

use GuzzleHttp\Client;
use JsonException;

class Track
{
    /**
     * Constant for API Base URI.
     */
    const BASE_URI = 'https://trackapi.thailandpost.co.th/post/api/v1/';

    /**
     * @var string
     */
    private $userToken = null;
    
    /**
     * @var string
     */
    private $apiToken = null;

    /**
     * @var int
     */
    private $apiTokenExpire = null;

    /**
     * @var GuzzleHttp\Client
     */
    private $client =  null;

    /**
     * Instantiate a new Thailand Post client.
     *
     * @param string        $userToken
     * @param double|null   $timeout
     */
    public function __construct($userToken, $timeout = 10.0)
    {
        $this->userToken = $userToken;
        $this->client = new Client([
            'base_uri' => BASE_URI,
            'timeout'  => $timeout,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function __destruct()
    {
    }

    /**
     * Get API Token
     *
     * @throws JsonException If get invalid JSON format from response.
     *
     * @return null|string Token
     */
    public function getToken()
    {
        $response = $this->client->request('POST', 'authenticate/token', [
            'headers' =>  [
                'Authorization' => 'Token ' . $this->apiToken
                ]
            ]);
        $result = [
            'code' => $response->getStatusCode(),
            'message' =>  $response->getStatusCode(),
            'data'  => null
        ];
        
        if ($result['code'] == 200) {
            $body = json_decode($response->getBody());
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new JsonException('Failed while encrypt the data to JSON.');
            }
            $this->apiToken = $body->token;
            $this->apiTokenExpire = strtotime($body->expire);
            $result['data'] = $body;
            return $result;
        }
        return $result;
    }
    
    /**
     * Get items tracking data
     *
     * @throws JsonException If get invalid JSON format from response.
     *
     * @return mixed
     */
    public function track($itemsCode = [])
    {
        $response = $this->client->request('POST', 'track', [
            'headers' =>  [
                'Authorization' => 'Token ' . $this->apiToken
                ]
            ]);
       
        $result = [
            'code' => $response->getStatusCode(),
            'message' =>  $response->getStatusCode(),
            'data'  => null
        ];
        
        if ($result['code'] == 200) {
            $body = json_decode($response->getBody());
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new JsonException('Failed while encrypt the data to JSON.');
            }
            $result['data'] = $body;
            return $result;
        }
        return $result;
    }
}
