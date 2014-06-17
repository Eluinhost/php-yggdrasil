<?php
namespace PublicUHC\PhpYggdrasil;

class PlayerProperties {

    private $timestamp;
    private $profileID;
    private $profileName;
    private $public;
    private $skinTexture;
    private $capeTexture;

    /**
     * Create a new properties for PlayerInformation
     *
     * @param $timestamp int
     * @param $profileID String player UUID
     * @param $profileName String player name
     * @param $public boolean ?
     * @param $skinTexture null|String skin texture URL, default null for no skin
     * @param $capeTexture null|String cape texture URL, default null for no cape
     */
    public function __construct($timestamp, $profileID, $profileName, $public = null, $skinTexture = null, $capeTexture = null)
    {
        $this->timestamp = $timestamp;
        $this->profileID = $profileID;
        $this->profileName = $profileName;
        $this->public = $public;
        $this->skinTexture = $skinTexture;
        $this->capeTexture = $capeTexture;
    }

    /**
     * @return int the timestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param $timestamp int timestamp to set to
     * @return PlayerProperties
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return String the player UUID
     */
    public function getProfileID()
    {
        return $this->profileID;
    }

    /**
     * @param $profileID String the player UUID
     * @return PlayerProperties
     */
    public function setProfileID($profileID)
    {
        $this->profileID = $profileID;
        return $this;
    }

    /**
     * @return String the player name
     */
    public function getProfileName()
    {
        return $this->profileName;
    }

    /**
     * @param String $profileName the player name
     * @return PlayerProperties
     */
    public function setProfileName($profileName)
    {
        $this->profileName = $profileName;
        return $this;
    }

    /**
     * @return boolean the values of isPublic or null of not set
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public the value if isPublic or null if not set
     * @return PlayerProperties
     */
    public function setPublic($public)
    {
        $this->public = $public;
        return $this;
    }

    /**
     * @return String the URL of the skin texture
     */
    public function getSkinTexture()
    {
        return $this->skinTexture;
    }

    /**
     * @param String $skinTexture the URL of the skin texture
     * @return PlayerProperties
     */
    public function setSkinTexture($skinTexture)
    {
        $this->skinTexture = $skinTexture;
        return $this;
    }

    /**
     * @return null|String the URL of the cape texture or null if not set
     */
    public function getCapeTexture()
    {
        return $this->capeTexture;
    }

    /**
     * @param null|String $capeTexture the URL of the cape texture or null if none set
     * @return PlayerProperties
     */
    public function setCapeTexture($capeTexture)
    {
        $this->capeTexture = $capeTexture;
        return $this;
    }

    /**
     * @return bool whether there is a cape URL set or not
     */
    public function hasCape()
    {
        return $this->capeTexture != null;
    }

    /**
     * @return bool whether there is a skin URL set or not
     */
    public function hasSkin()
    {
        return $this->skinTexture != null;
    }
} 