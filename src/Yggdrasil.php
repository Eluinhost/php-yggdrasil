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
     * Sets the accessToken and clientToken values on success.
     *
     * @param $agent String the game to authenticate with. Currently 'Minecraft' or 'Scrolls'. Defaults to 'Minecraft'
     * @param $password String the password to match the username
     * @throws InvalidParameterException if the username isn't set or $password is null
     * @throws APIRequestException if the server returned errors for the request
     */
    function authenticate($password, $agent = 'Minecraft');

    /**
     * Refresh the access token. Can be used to verify an access token is correct
     *
     * Sets the accessToken and clientToken values on success.
     *
     * @throws InvalidParameterException if clientToken or accessToken are not set
     * @throws APIRequestException if the server returned errors for the request
     */
    function refresh();

    /**
     * Checks if an accessToken is a valid session token with a currently-active session.
     * Note: this method will not respond successfully to all currently-logged-in sessions,
     * just the most recently-logged-in for each user. It is intended to be used by servers
     * to validate that a user should be connecting (and reject users who have logged in
     * elsewhere since starting Minecraft), NOT to auth that a particular session token is
     * valid for authentication purposes. To authenticate a user by session token, use the
     * refresh verb and catch resulting errors.
     *
     * @throws InvalidParameterException when accessToken is not set
     */
    function validate();

    /**
     * Sign the account out
     *
     * @param $password String the user's password
     * @throws InvalidParameterException if the username isn't set or $password is null
     * @throws APIRequestException if the server returned errors for the request
     */
    function signout($password);

    /**
     * Invalidates the client/access token pair
     *
     * @throws InvalidParameterException TODO condition
     * @throws APIRequestException if the server returned errors for the request
     * @return mixed TODO
     */
    function invalidate();
} 