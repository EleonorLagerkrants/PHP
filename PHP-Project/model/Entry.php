<?php

/**
 * Created by PhpStorm.
 * User: Eleonor Lagerkrants
 * Date: 2015-10-16
 * Time: 15:00
 */

require_once("model/EntryDAL.php");

class Entry {

    private $ID;
    private $text;
    private $name;
    public $dal;

    public function __construct($name, $text, $ID) {
        $this->text = $text;
        $this->name = $name;
        $this->ID = $ID;
        $this->dal = new EntryDAL();
    }

    public function saveEntry() {
        return $this->dal->save($this->name, $this->ID, $this->text);
    }

    public function getName() {
        return $this->name;
    }

    public function getID() {
        return $this->ID;
    }

    public function getText() {
        return $this->text;
    }

}