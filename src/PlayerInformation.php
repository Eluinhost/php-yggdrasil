<?php
namespace PublicUHC\PhpYggdrasil;


class PlayerInformation {

    private $id;
    private $name;
    private $properties;
    private $isLegacy = false;
    private $isDemo = false;

    /**
     * Create a new PlayerInformation result. Legacy and Demo are set to false by default, use setIsLegacy and setIsDemo to set them.
     *
     * @param $id String the player UUID
     * @param $name String the player name
     * @param PlayerProperties $properties the associated properties
     */
    public function __construct($id, $name, PlayerProperties $properties)
    {
        $this->id = $id;
        $this->name = $name;
        $this->properties = $properties;
    }

    /**
     * @return PlayerProperties the properties of the player information
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param PlayerProperties $properties the properties of the player information
     * @return $this;
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @return String the player name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name the player name
     * @return $this;
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return String the player UUID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param String $id the player UUID
     * @return $this;
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool true if legacy account
     */
    public function isLegacy()
    {
        return $this->isLegacy;
    }

    /**
     * @param $legacy bool true if legacy account, false otherwise
     * @return $this
     */
    public function setIsLegacy($legacy)
    {
        $this->isLegacy = $legacy;
        return $this;
    }

    /**
     * @return boolean true if demo account
     */
    public function getIsDemo()
    {
        return $this->isDemo;
    }

    /**
     * @param boolean $isDemo true if demo account, false otherwise
     * @return $this;
     */
    public function setIsDemo($isDemo)
    {
        $this->isDemo = $isDemo;
        return $this;
    }


}