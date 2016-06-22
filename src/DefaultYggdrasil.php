<?php
namespace PublicUHC\PhpYggdrasil;

use GuzzleHttp\Client;

class DefaultYggdrasil implements Yggdrasil {

    const AUTH_SERVER_URL = 'https://authserver.mojang.com';
    const SESSION_SERVER = 'https://sessionserver.mojang.com/session/minecraft';

    private $username;
    private $httpClient;

    //these properties will be filled after a sucessfull response
    private $clientToken;
    private $accessToken;
    private $selectedProfile;

    /**
     * Create a new Yggdrasil for querying mojang servers
     *
     * @param null|String $username (optional) The email address (or username of legacy accounts) of the user
     * @param null|String $clientToken (optional) The assosciated client token
     * @param null|String $accessToken (optional) The associated access token
     */
    public function __construct($username = null, $clientToken = null, $accessToken = null)
    {
        $this->username = $username;
        $this->clientToken = $clientToken;
        $this->accessToken = $accessToken;

        $this->httpClient = new Client();
    }

    /**
     * Checks the array for a properties with name 'textures' and creates a PlayerProperties for it
     *
     * @param $propertiesArray array the array of properties
     * @return PlayerProperties the parsed properties or null if textures property not found
     */
    private function parseTexturesProperties($propertiesArray)
    {
        foreach($propertiesArray as $property) {
            if($property['name'] == 'textures') {
                $texturesJSON = json_decode(base64_decode($property['value']), true);

                $properties = new PlayerProperties(
                    $texturesJSON['timestamp'],
                    $texturesJSON['profileId'],
                    $texturesJSON['profileName']
                );

                if(isset($texturesJSON['isPublic'])) {
                    $properties->setPublic($texturesJSON['isPublic']);
                }

                if(array_key_exists('SKIN', $texturesJSON['textures'])) {
                    $properties->setSkinTexture($texturesJSON['textures']['SKIN']['url']);
                }
                if(array_key_exists('CAPE', $texturesJSON['textures'])) {
                    $properties->setCapeTexture($texturesJSON['textures']['CAPE']['url']);
                }

                return $properties;
            }
        }
        return null;
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
        return $this->getResponse(
            self::AUTH_SERVER_URL . $subURL,
            [
                'json' => $jsonData,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ],
            true
        );
    }

    /**
     * Shortcut to getResponse using self::SESSION_SERVER_URL as a base
     *
     * @param $subURL String the url to append to SESSION_SERVER_URL
     * @param $queryParameters array an assoc array of the get parameters to set
     * @throws APIRequestException if a non 200 code with the error details from the server
     * @return array json response
     */
    private function getSessionServerResponse($subURL, $queryParameters = [])
    {
        return $this->getResponse(
            self::SESSION_SERVER . $subURL,
            [
                'query' => $queryParameters
            ],
            false
        );
    }

    /**
     * Get a response from the given subURL via POST with the given JSON data. Sets header Content-Type for JSON
     *
     * @param $url String the full URL to request
     * @param $options array the options to set on the request
     * @param $post boolean if true uses POST, otherwise uses GET
     * @throws APIRequestException if a non 200 code with the error details from the server
     * @return array json response
     */
    private function getResponse($url, $options, $post)
    {
        if($post) {
            $response = $this->httpClient->post($url, $options);
        } else {
            $response = $this->httpClient->get($url, $options);
        }
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

    public function getSelectedProfile()
    {
        return $this->selectedProfile;
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

        $selectedResponse = $response['selectedProfile'];
        $uuid = $selectedResponse['id'];
        $playerName = $selectedResponse['name'];
        //only appears if true
        $legacy = isset($selectedResponse['legacy']);
        $this->selectedProfile = new Profile($uuid, $playerName, $legacy);
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

        $selectedResponse = $response['selectedProfile'];
        $uuid = $selectedResponse['id'];
        $playerName = $selectedResponse['name'];
        //only appears if true
        $legacy = isset($selectedResponse['legacy']);
        $this->selectedProfile = new Profile($uuid, $playerName, $legacy);
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

        $response = $this->getSessionServerResponse("/profile/$uuid");

        $properties = $this->parseTexturesProperties($response['properties']);

        $playerInformation = new PlayerInformation($response['id'], $response['name'], $properties);

        if(array_key_exists('legacy', $response)) {
            $playerInformation->setIsLegacy($response['legacy']);
        }

        if(array_key_exists('demo', $response)) {
            $playerInformation->setIsDemo($response['demo']);
        }

        return $playerInformation;
    }

    function hasJoined($username, $loginHash)
    {
        if($username == null)
            throw new InvalidParameterException('Cannot send hasJoined for a null username');
        if($loginHash == null)
            throw new InvalidParameterException('Cannot send hasJoined for a null login hash');

        $response = $this->getSessionServerResponse("/hasJoined", [
            'username' => $username,
            'serverId' => $loginHash
        ]);

        $properties = $this->parseTexturesProperties($response['properties']);

        return new HasJoinedResponse($response['id'], $properties);
    }
}
