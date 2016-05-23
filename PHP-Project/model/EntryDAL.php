<?php

/**
 * Created by PhpStorm.
 * User: Eleonor Lagerkrants
 * Date: 2015-10-16
 * Time: 15:01
 */
class EntryDAL {

    public function save($name, $ID, $text) {
        if(file_exists(self::getFileName($name, $ID))) {
            return false;
        } else {
            file_put_contents(self::getFileName($name, $ID), $text);
            return true;
        }
    }

    public function load($name, $ID) {
        if(file_exists(self::getFileName($name, $ID)))
            return file_get_contents(self::getFileName($name, $ID));
    }

    public function getFileName($name, $ID) {
        return Settings::ENTRYPATH . addslashes($name) . "/" .$ID;
    }

    /**
     * Creates and returns an array of the current users entries
     */
    public function getEntries() {
        $name = "";
        if(isset($_SESSION['username']))
            $name = $_SESSION['username'];
        $ID = "";
        $entries = "";
        if(file_exists(Settings::ENTRYPATH . addslashes($name) . "/index"))
            $ID = file_get_contents(Settings::ENTRYPATH . addslashes($name) . "/index") - 1;
        while($ID != 0) {
            $entry = new Entry($name, $this->load($name, $ID), $ID);
            $entries[] = $entry;
            $ID = $ID - 1;
        }
        return $entries;

    }

}