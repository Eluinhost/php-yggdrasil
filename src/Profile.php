<?php

namespace PublicUHC\PhpYggdrasil;

class Profile
{

    private $profileid;
    private $name;
    private $legacy;

    public function __construct($profileid, $name, $legacy)
    {
        if (!is_string($profileid) || !is_string($name) || !is_bool($legacy))
            throw new \Exception("Invalid arguments");

        $this->profileid = $profileid;
        $this->name = $name;
        $this->legacy = $legacy;
    }

    /**
     * @return string uuid of the profile without dashes
     */
    public function getProfileId()
    {
        return $this->profileid;
    }

    /**
     * @return string most recent player name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool this is true if the account isn't migrated to mojang
     */
    public function isLegacy()
    {
        return $this->legacy;
    }
}
