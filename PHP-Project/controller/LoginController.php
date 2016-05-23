<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */

require_once("model/LoginModel.php");
require_once("view/LoginView.php");

class LoginController {

	private $model;
	private $loginView;
    private $viewEntryView;

	public function __construct(LoginModel $model, LoginView $loginView, ViewEntryView $viewEntryView) {
		$this->model = $model;
		$this->loginView =  $loginView;
        $this->viewEntryView = $viewEntryView;
	}

	public function doLoginControl() {
		
		$userClient = $this->loginView->getUserClient();
		if ($this->model->isLoggedIn($userClient)) {

			if($this->loginView->userWantsToView()) {
                $entry = $this->loginView->getMenuURL();
                $this->viewEntryView->setCurrentEntry($entry);
                $this->loginView->setClickedItem();
			}

			if ($this->loginView->userWantsToLogout()) {
				$this->model->doLogout();
				$this->loginView->setUserLogout();
			}
		}
		else {
			if ($this->loginView->userWantsToLogin() && $this->loginView->checkForm()) {
				$uc = $this->loginView->getCredentials();
				if ($this->model->doLogin($uc) == true) {
                    $name = $uc->getName();
                    $this->loginView->setSessionName($name);
					$this->loginView->setLoginSucceeded();
				} else {
					$this->loginView->setLoginFailed();
				}
			}
		}
		$this->model->renew($userClient);
	}
}