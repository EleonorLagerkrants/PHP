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
            $folderName = self::getFolderName($name);
            mkdir($folderName, 0755, true);
            file_put_contents($folderName . "/index", 1);
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

    public function getFolderName($name) {
        return Settings::ENTRYPATH . addslashes($name);
    }
}