<?php
namespace PublicUHC\PhpYggdrasil;


use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\ResponseInterface;

class DefaultYggdrasil implements Yggdrasil {

    const AUTH_SERVER_URL = 'https://authserver.mojang.com';

    private $username;
    private $clientToken;
    private $accessToken;
    private $httpClient;

    public function __construct($username, $clientToken = null, $accessToken = null)
    {
        $this->username = $username;
        $this->clientToken = $clientToken;
        $this->accessToken = $accessToken;
        $this->httpClient = new Client();
    }

    /**
     * Get a response from the given subURL via POST with the given JSON data. Sets header Content-Type for JSON
     *
     * @param $subURL String the sub url to add onto AUTH_SERVER_URL
     * @param $jsonData array the json payload
     * @return ResponseInterface the returned response
     */
    private function getResponse($subURL, $jsonData) {
        return $this->httpClient->post(
            self::AUTH_SERVER_URL . $subURL,
            [
                'json'      => $jsonData,
                'headers'   => [
                    'Content-Type' => 'application/json'
                ]
            ]
        );
    }

    public function getClientToken()
    {
        return $this->clientToken;
    }

    public function setClientToken($token)
    {
        $this->clientToken = $token;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($token)
    {
        $this->accessToken = $token;
        return $this;
    }

    function getUsername()
    {
        return $this->username;
    }

    function authenticate($password)
    {
        // TODO: Implement authenticate() method.
    }

    function refresh()
    {
        // TODO: Implement refresh() method.
    }

    function validate()
    {
        // TODO: Implement validate() method.
    }

    function signout($password)
    {
        // TODO: Implement signout() method.
    }

    function invalidate()
    {
        // TODO: Implement invalidate() method.
    }
}