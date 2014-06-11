<?php
namespace PublicUHC\PhpYggdrasil;


class DefaultYggdrasil implements Yggdrasil {

    const AUTH_SERVER_URL = 'https://authserver.mojang.com';

    private $username;
    private $clientToken;
    private $accessToken;

    public function __construct($username, $clientToken = null, $accessToken = null)
    {
        $this->username = $username;
        $this->clientToken = $clientToken;
        $this->accessToken = $accessToken;
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