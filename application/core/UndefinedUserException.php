<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UndefinedUserException extends Exception {

    public function __construct($message, Exception $previous = null) {

        parent::__construct("Missing value for key \"user\" or ".$message, "4", $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n"; //edit this to your need
    }

}
