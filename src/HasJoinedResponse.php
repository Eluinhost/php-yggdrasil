<?php
namespace PublicUHC\PhpYggdrasil;

class HasJoinedResponse {

    private $uuid;
    private $properties;

    /**
     * Create a new HasJoinedResponse
     *
     * @param $uuid String the UUID of the user
     * @param PlayerProperties $properties the associated properties
     */
    public function __construct($uuid, PlayerProperties $properties)
    {
        $this->uuid = $uuid;
        $this->properties = $properties;
    }

    /**
     * @return String the UUID of the user
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param String $uuid the UUID of the user
     * @return $this;
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return PlayerProperties associated properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param PlayerProperties $properties associated properties
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }
} 