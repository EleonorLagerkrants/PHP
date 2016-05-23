<?php

require_once("UserDAL.php");

class User {

    private $name;
    private $password;
    public $dal;

    public function __construct($name, $pass) {
        $this->name = $name;
        $this->password = $pass;
        $this->dal = new UserDAL();
    }

    public function registerUser() {
        return $this->dal->register($this->name, $this->password);
    }

    public function getUsername() {
        return $this->name;
    }

    public function getPassword() {
        return $this->password;
    }

}