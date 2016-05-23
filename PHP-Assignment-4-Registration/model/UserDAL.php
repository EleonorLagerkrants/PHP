<?php

class UserDAL {

    /**
     * Saves the user
     */
    public function register($name, $password) {
        if(file_exists(self::getFileName($name)))
            return false;
        else {
            file_put_contents(self::getFileName($name), serialize($password));
            return true;
        }
    }

    public function load($name) {
        if(file_exists(self::getFileName($name)))
            return unserialize(file_get_contents(self::getFileName($name)));
    }

    public function getFileName($name) {
        return Settings::USERPATH . addslashes($name);
    }

}