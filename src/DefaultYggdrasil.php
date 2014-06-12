<?php
namespace PublicUHC\PhpYggdrasil;


use GuzzleHttp\Client;

class DefaultYggdrasil implements Yggdrasil {

    const AUTH_SERVER_URL = 'https://authserver.mojang.com';
    const SESSION_SERVER = 'https://sessionserver.mojang.com/session/minecraft/profile';

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
     * Shortcut to getResponse using self::AUTH_SERVER_URL as a base
     *
     * @param $subURL String the url to append to AUTH_SERVER_URL
     * @param $jsonData array the json payload
     * @throws APIRequestException if a non 200 code with the error details from the server
     * @return array json response
     */
    private function getAuthServerResponse($subURL, $jsonData)
    {
        return $this->getResponse(self::AUTH_SERVER_URL . $subURL, $jsonData);
    }

    /**
     * Shortcut to getResponse using self::SESSION_SERVER_URL as a base
     *
     * @param $subURL String the url to append to SESSION_SERVER_URL
     * @param $jsonData array the json payload
     * @throws APIRequestException if a non 200 code with the error details from the server
     * @return array json response
     */
    private function getSessionServerResponse($subURL, $jsonData)
    {
        return $this->getResponse(self::SESSION_SERVER . $subURL, $jsonData);
    }

    /**
     * Get a response from the given subURL via POST with the given JSON data. Sets header Content-Type for JSON
     *
     * @param $url String the full URL to request
     * @param $jsonData array the json payload
     * @throws APIRequestException if a non 200 code with the error details from the server
     * @return array json response
     */
    private function getResponse($url, $jsonData)
    {
        $response =  $this->httpClient->post(
            $url,
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
            throw new InvalidParameterException('Username has not been set, cannot authenticate');
        if($password == null)
            throw new InvalidParameterException('Password cannot be null when authenticating');

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

        $response = $this->getAuthServerResponse('/authenticate', $payload);

        $this->accessToken = $response['accessToken'];
        $this->clientToken = $response['clientToken'];
    }

    function refresh()
    {
        if ($this->clientToken == null)
            throw new InvalidParameterException('Client token has not been set, cannot refresh.');
        if ($this->accessToken == null)
            throw new InvalidParameterException('Access token has not been set, cannot refresh.');

        $response = $this->getAuthServerResponse('/refresh', [
            'accessToken' => $this->accessToken,
            'clientToken' => $this->clientToken
        ]);

        $this->accessToken = $response['accessToken'];
        $this->clientToken = $response['clientToken'];
    }

    function validate()
    {
        if($this->accessToken == null)
            throw new InvalidParameterException('Access token has not been set, cannot validate.');

        $this->getAuthServerResponse('/validate', ['accessToken' => $this->accessToken]);
    }

    function signout($password)
    {
        if($this->username == null)
            throw new InvalidParameterException('Username has not been set, cannot signout');
        if($password == null)
            throw new InvalidParameterException('Password cannot be null when signout');

        $this->getAuthServerResponse('/signout', [
            'username' => $this->username,
            'password' => $password
        ]);
    }

    function invalidate()
    {
        if ($this->clientToken == null)
            throw new InvalidParameterException('Client token has not been set, cannot invalidate.');
        if ($this->accessToken == null)
            throw new InvalidParameterException('Access token has not been set, cannot invalidate.');

        $this->getAuthServerResponse('/invalidate', [
            'clientToken' => $this->clientToken,
            'accessToken' => $this->accessToken
        ]);
    }

    function getPlayerInfo($uuid)
    {
        if ($uuid == null)
            throw new InvalidParameterException('Cannot fetch info for a null uuid');

        $response = $this->getSessionServerResponse("/$uuid", []);

        $properties = null;

        foreach($response['properties'] as $property) {
            if($property['name'] == 'textures') {
                $texturesJSON = base64_decode($property['value']);
                $properties = new PlayerProperties(
                    $texturesJSON['timestamp'],
                    $texturesJSON['profileId'],
                    $texturesJSON['profileName'],
                    $texturesJSON['isPublic'],
                    $texturesJSON['textures']['SKIN']['url']
                );
                if($texturesJSON['textures']['CAPE'] != null) {
                    $properties->setCapeTexture($texturesJSON['textures']['CAPE']['url']);
                }
            }
        }

        $playerInformation = new PlayerInformation($response['id'], $response['name'], $properties);

        if($response['legacy'] != null) {
            $playerInformation->setIsLegacy($response['legacy']);
        }

        if($response['demo'] != null) {
            $playerInformation->setIsDemo($response['demo']);
        }

        return $playerInformation;
    }
}