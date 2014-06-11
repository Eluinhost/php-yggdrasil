<?php
namespace PublicUHC\PhpYggdrasil;


class APIRequestException extends \Exception {

    private $shortMessage;
    private $cause;

    public function __construct($shortMessage, $errorMessage, $cause = '')
    {
        parent::__construct($errorMessage);
        $this->shortMessage = $shortMessage;
        $this->cause = $cause;
    }

    public function getShortMessage()
    {
        return $this->shortMessage;
    }

    public function getCause()
    {
        return $this->cause;
    }
}