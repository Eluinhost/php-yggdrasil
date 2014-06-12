<?php
namespace PublicUHC\PhpYggdrasil;

use PHPUnit_Framework_TestCase;

class DefaultYggdrasilTest extends PHPUnit_Framework_TestCase {

    public function testPlayerInformationFetchDemoAccount()
    {
        $yggdrasil = new DefaultYggdrasil();

        $accountUUID = '9ff3d74f716940a3aa6f262ab632d2de';

        $playerInformation = $yggdrasil->getPlayerInfo($accountUUID);

        $this->assertEquals($playerInformation->getId(), $accountUUID);
        $this->assertEquals($playerInformation->getName(), 'redstonesheep');
        $this->assertEquals($playerInformation->isDemo(), true);
        $this->assertEquals($playerInformation->isLegacy(), true);

        $properties = $playerInformation->getProperties();

        $this->assertNotNull($properties);
        $this->assertEquals($properties->getProfileID(), $accountUUID);
        $this->assertEquals($properties->getProfileName(), 'redstonesheep');
        //cant really verify that the timestamp/public inside properties will be the same

        $this->assertNull($properties->getSkinTexture());
        $this->assertNull($properties->getCapeTexture());
        $this->assertFalse($properties->hasCape());
        $this->assertFalse($properties->hasSkin());
    }

    public function testPlayerInformationLegacyAccount()
    {
        $yggdrasil = new DefaultYggdrasil();

        $accountUUID = '048fa31030de44fe9f5ec7443e91ad46'; //my ghowden account UUID

        $playerInformation = $yggdrasil->getPlayerInfo($accountUUID);

        $this->assertEquals($playerInformation->getId(), $accountUUID);
        $this->assertEquals($playerInformation->getName(), 'ghowden');
        $this->assertFalse($playerInformation->isDemo());
        $this->assertTrue($playerInformation->isLegacy());

        $properties = $playerInformation->getProperties();

        $this->assertNotNull($properties);
        $this->assertEquals($properties->getProfileID(), $accountUUID);
        $this->assertEquals($properties->getProfileName(), 'ghowden');
        //cant really verify the timestamp/public properties

        $this->assertNotNull($properties->getSkinTexture());
        $this->assertNull($properties->getCapeTexture());
        $this->assertTrue($properties->hasSkin());
        $this->assertFalse($properties->hasCape()); //no cape :(
    }

    public function testPlayerInformationCape()
    {
        $yggdrasil = new DefaultYggdrasil();

        $accountUUID = '74ed335012fe4729a8923d9b15f94506'; //shadoune!!!

        $playerInformation = $yggdrasil->getPlayerInfo($accountUUID);

        $this->assertEquals($playerInformation->getName(), 'Shadoune666');
        $this->assertFalse($playerInformation->isDemo());
        $this->assertFalse($playerInformation->isLegacy());

        $properties = $playerInformation->getProperties();

        $this->assertNotNull($properties);
        $this->assertEquals($properties->getProfileID(), $accountUUID);
        $this->assertEquals($properties->getProfileName(), 'Shadoune666');
        //cant really verify the timestamp/public properties

        $this->assertNotNull($properties->getSkinTexture());
        $this->assertNotNull($properties->getCapeTexture());
        $this->assertTrue($properties->hasSkin());
        $this->assertTrue($properties->hasCape());
    }
} 