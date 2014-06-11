<?php
namespace PublicUHC\PhpYggdrasil;


use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;

class DefaultYggdrasil implements Yggdrasil {

    const AUTH_SERVER_URL = 'https://authserver.mojang.com';

    private $username;
    private $clientToken;
    private $accessToken;
    private $httpClient;

    /**
     * Create a new Yggdrasil for querying mojang servers
     *
     * @param null|String $username (optional) The email address (or username of legacy accounts) of the user
     * @param null|String $clientToken (optional) The assosciated client token
     * @param null|String $accessToken (optional) The associated access token
     */
    public function __construct($username = null, $clientToken = null, $accessToken = null)
    {
        $this->httpClient = new Client();
    }

    /**
     * Get a response from the given subURL via POST with the given JSON data. Sets header Content-Type for JSON
     *
     * @param $subURL String the sub url to add onto AUTH_SERVER_URL
     * @param $jsonData array the json payload
     * @throws APIRequestException if a non 200 code with the error details from the server
     * @return array json response
     */
    private function getResponse($subURL, $jsonData) {
        $response =  $this->httpClient->post(
            self::AUTH_SERVER_URL . $subURL,
            [
                'json'      => $jsonData,
                'headers'   => [
                    'Content-Type' => 'application/json'
                ]
            ]
        );
        if( $response->getStatusCode() != 200 ) {
            $json = $response->json();
            $short = $json['error'];
            $error = $json['errorMessage'];
            $cause = $json['cause'];
            throw new APIRequestException(
                $short == null ? 'Unknown Error' : $short,
                $error == null ? 'Unknown Error' : $error,
                $cause == null ? '' : $cause
            );
        }
        return $response->json();
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

    function authenticate($password, $agent = 'Minecraft')
    {
        if($this->username == null)
            throw new InvalidParameterException('Username field has not been set, use setUsername (or the constructor) to set it before trying to authenticate.');
        if($password == null)
            throw new InvalidParameterException('Cannot authenticate with a null password.');

        $payload = [
            'agent' => [
                'name'      => $agent,
                'version'   => 1
            ],
            'username' => $this->username,
            'password' => $password
        ];

        if($this->clientToken != null) {
            $payload['client_token'] = $this->clientToken;
        }

        $response = $this->getResponse('/authenticate', $payload);

        $this->accessToken = $response['accessToken'];
        $this->clientToken = $response['clientToken'];
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