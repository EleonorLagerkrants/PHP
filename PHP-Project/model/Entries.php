<?php

require_once("model/EntryDAL.php");

class Entries {

    private $dal;
    private $entries;

    public function __construct(EntryDAL $entryDAL) {
        $this->dal = $entryDAL;
    }

    public function getEntries() {
        $this->entries = $this->dal->getEntries();
        return $this->entries;
    }

}