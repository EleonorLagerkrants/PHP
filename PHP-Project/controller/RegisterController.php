<?php

require_once("view/RegistrationView.php");

class RegisterController {

    private $regView;
    private $loginView;

    public function __construct(RegistrationView $regView, LoginView $loginView) {
        $this->regView = $regView;
        $this->loginView = $loginView;
    }

    public function doRegistrationControl() {
        if ($this->regView->userWantsToRegister() && $this->regView->checkForm()) {
            $user = $this->regView->getUser();
            if ($user->registerUser() == true) {
                $this->regView->setRegSuccess();
                $this->loginView->setUserRegistration();
            }
            else
                $this->regView->setRegFail();
        }
    }
}