<?php

require_once("view/EntryView.php");

class EntryController {

    private $entryView;
    private $loginView;

    public function __construct(EditView $entryView, LoginView $loginView) {
        $this->entryView = $entryView;
        $this->loginView = $loginView;
    }

    public function doEntryControl() {
        $this->entryView->setID($this->loginView->getLoggedName());
        if ($this->entryView->userWantsToSave() && $this->entryView->checkForm()) {
            $entry = $this->entryView->getEntry($this->loginView->getLoggedName());
            if($entry->saveEntry() == true) {
               $this->entryView->setSaveSuccess();
               $this->entryView->increaseID($this->loginView->getLoggedName());
            }
            else
                $this->entryView->setSaveFail();
        }
    }
}