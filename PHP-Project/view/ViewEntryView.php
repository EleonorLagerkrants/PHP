<?php

class ViewEntryView {

    private $currentEntry;

    private function generateViewForm($title, $ID, $text) {
        return "<div class='form-style-2'>
                <div class='form-style-2-heading'>View Entry</div>
                <form method='post' enctype='multipart/form-data'>
                        <input type='text' class='input-field' readonly value='Title: $title'</label>
                        <div class='form-style-2-text'>Entry nr: " .$ID. "</div>
                        <textarea class='textarea-field' readonly> ". $text ." </textarea>
                        <br>
                </form>
                </div>
                ";
    }

    public function response() {
        return $this->doEditForm();
    }

    private function doEditForm() {
        $entry = $this->currentEntry;
        $text = $entry->getText();
        $entryText = explode("\r\n", $text);
        $title = $entryText[0];
        $ID = $entry->getID();
        $text = $entryText[1];
        return $this->generateViewForm($title, $ID, $text);
    }

    public function getStartLink() {
        return '<a href="?">Back to start</a>';
    }

    public function setCurrentEntry(Entry $entry) {
        $this->currentEntry = $entry;
    }

}