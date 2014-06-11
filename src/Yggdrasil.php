<?php
namespace PublicUHC\PhpYggdrasil;

interface Yggdrasil {

    /**
     * @return null|String the client token if set
     */
    function getClientToken();

    /**
     * @param $token string the token to set to
     * @return $this
     */
    function setClientToken($token);

    /**
     * @return null|String the access token if set
     */
    function getAccessToken();

    /**
     * @param $token string the token to set to
     * @return $this
     */
    function setAccessToken($token);

    /**
     * @return null|String the client username
     */
    function getUsername();

    /**
     * Gets an access token from the server, can be fetched with getAccessToken.
     *
     * <p>If clientToken is set it will be passed along with the authenticate function</p>
     * <p>
     * If it is not set the server will generate a random token based on Java's UUID.toString()
     * This will however also invalidate all previously acquired accessTokens for this user across all clients.
     * </p>
     *
     * @param $agent String the game to authenticate with. Currently 'Minecraft' or 'Scrolls'. Defaults to 'Minecraft'
     * @param $password String the password to match the username
     * @return mixed TODO
     */
    function authenticate($password, $agent = 'Minecraft');

    /**
     * Refresh the access token. Can be used to verify an access token is correct
     * @return mixed TODO
     */
    function refresh();

    /**
     * Checks if an the access token is the latest for the account
     * @return mixed TODO
     */
    function validate();

    /**
     * Sign the account out
     * @param $password String the user's password
     * @return mixed TODO
     */
    function signout($password);

    /**
     * Invalidates the client/access token pair
     * @return mixed TODO
     */
    function invalidate();
} 