<?php

/**
 * Created by PhpStorm.
 * User: Eleonor Lagerkrants
 * Date: 2015-10-16
 * Time: 12:32
 */

require_once("model/Entry.php");

class EditView {

    private static $text = "EntryView::Text";
    private static $title = "EntryView::Title";
    private static $save = "EntryView::Save";

    private static $entryLink = "entry";

    /**
     * Displays the current user entry ID
     */
    private $idValue;

    /**
     * Variables that are controlled through setters
     */
    private $saveSuccess = false;
    private $saveFail = false;

    private static $sessionSaveLocation;
    private static $userSaveLocation;

    public function __construct() {
        self::$sessionSaveLocation = Settings::MESSAGE_SESSION_NAME . Settings::APP_SESSION_NAME;
        self::$userSaveLocation = Settings::USER_SESSION_NAME . Settings::APP_SESSION_NAME;
    }

    private function generateEditForm($message) {
        return "<div class='form-style-2'>
                <div class='form-style-2-heading'>New Entry</div>
                <form action='?entry' method='post' enctype='multipart/form-data'>
                    <div class='form-style-2-text'>Write a new entry</div>
                        <div class='form-style-2-message'>" .$message. "</div>
					    <input type='text' class='input-field' placeholder='Title' id='". self::$title ."' name='". self::$title ."'/>
                        <div class='form-style-2-text'>Entry nr: " .$this->idValue. "</div>
                        <br>
                        <textarea name='". self::$text ."' class='textarea-field' id='". self::$text ."' placeholder='Write something...'></textarea>
                        <br>
                        <input id='submit' type='submit' name='". self::$save ."' value='Save'>
                        <br>
                    </fieldset>
                </form>";
    }

    public function response() {
        return $this->doEditForm();
    }

    private function doEditForm() {
        $message = "";
        if ($this->userWantsToSave()) {
            if(strlen($this->getTitle()) < 3) {
                $message .= "Title should be more than 3 characters. ";
            }
            if(strlen($this->getTitle()) > 20) {
                $message .= "Title should be less than 20 characters. ";
            }
            if(strlen($this->getText()) < 1) {
                $message .= "Entry content cannot be empty. ";
            }
        }
        return $this->generateEditForm($message);
    }

    public function getEntryLink() {
        return '<a href="?'.self::$entryLink.'">Write a new entry</a>';
    }

    public function getStartLink() {
        return '<a href="?">Back to start</a>';
    }

    /**
     * Reads user index file and sets idValue
     */
    public function setID($name) {
        if(file_exists(Settings::ENTRYPATH . addslashes($name) . "/index"))
            $this->idValue = file_get_contents(Settings::ENTRYPATH . addslashes($name) . "/index");
    }

    public function saveSuccess() {
        return $this->saveSuccess;
    }

    /**
     * Checks if the entry form is valid
     */
    public function checkForm() {
        return strlen($this->getTitle()) > 2 && strlen($this->getTitle()) < 21 && strlen($this->getText()) > 0;
    }

    public function setSaveSuccess() {
        unset($_GET[self::$entryLink]);
        $this->saveSuccess = true;
        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        header("Location: $actual_link");
    }

    public function setSaveFail() {
        $this->saveFail = true;
    }

    public function getEntry($name) {
        $text = $this->getTitle() . "\r\n" . $this->getText();
        return new Entry($name, $text, $this->idValue);
    }

    public function userWantsToSave() {
        return isset($_POST[self::$save]);
    }

    private function getTitle() {
        if(isset($_POST[self::$title])) {
            return $_POST[self::$title];
        }
        return "";
    }

    private function getText() {
        if(isset($_POST[self::$text])) {
            return $_POST[self::$text];
        }
        return "";
    }

    public function increaseID($name) {
        if(file_exists(Settings::ENTRYPATH . addslashes($name) . "/index"))
            file_put_contents(Settings::ENTRYPATH . addslashes($name) . "/index", $this->idValue + 1);
        $this->setID($name);
    }

    public function clickedNewEntry() {
        return isset($_GET[self::$entryLink]);
    }

}