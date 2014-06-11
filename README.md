php-yggdrasil
=============

PHP Library for interacting with Mojang authentication servers.

Usage
-----

First you need to get hold of a `Yggdrasil` instance, the only choice right now is `DefaultYggdrasil`

    $yggdrasil = new DefaultYddrasil();
    
You can also pass the username/clientToken/accessToken in the constructor if needed, or alternatively use the setter methods.

You can then use the Yggdrasil instance to query against the server:

    //set the username
    $yggdrasil->setUsername('joe@blogs.com');
    
    //authenticate with the password, sets accessToken/clientToken on success
    $yggdrasil->authenticate('joeisthebest');
    
    $clientToken = $yggdrasil->getClientToken();
    $accessToken = $yggdrasil->getAccessToken();
    
Error Checking
--------------

All API functions can throw the following:

`APIRequestException`

Thrown when the mojang servers returned errors for the request, 
the short error can be found at $ex->getShortMessage(), 
the full message can get found at $ex->getMessage(),
and the cause (if set) can be found at $ex->getCause()

`InvalidParameterException`

Thrown when an API method was called and certain parameters were not set yet. e.g.
    
    //username not set yet
    $yg = new DefaultYggdrasil();
    
    //will throw InvalidParameterException due to username not being set
    $yg->authenticate('xxx');
    
    $yg->setUsername('yyy');
    
    //will run correctly
    $yg->authenticate('xxx');