<?php

class ApplicationController {

    private $loginController;
    private $registerController;
    private $entryController;

    public function __construct(LoginController $loginController, RegisterController $registerController, EntryController $entryController) {
        $this->loginController = $loginController;
        $this->registerController = $registerController;
        $this->entryController = $entryController;
    }

    public function doControl(){
        $this->loginController->doLoginControl();
        $this->registerController->doRegistrationControl();
        $this->entryController->doEntryControl();
    }


}