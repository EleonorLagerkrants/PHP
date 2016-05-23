<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */

require_once("model/LoginModel.php");
require_once("view/LoginView.php");

class LoginController {

	private $model;
	private $view;
	private $regView;

	public function __construct(LoginModel $model, LoginView $view, RegistrationView $regView) {
		$this->model = $model;
		$this->view =  $view;
		$this->regView = $regView;
	}

	public function doControl() {
		
		$userClient = $this->view->getUserClient();

		if ($this->model->isLoggedIn($userClient)) {
			if ($this->view->userWantsToLogout()) {
				$this->model->doLogout();
				$this->view->setUserLogout();
			}
		} else {
			
			if ($this->view->userWantsToLogin()) {
				$uc = $this->view->getCredentials();
				if ($this->model->doLogin($uc) == true) {
					$this->view->setLoginSucceeded();
				} else {
					$this->view->setLoginFailed();
				}
			}

			else if ($this->regView->userWantsToRegister() && $this->regView->checkForm()) {
                $user = $this->regView->getUser();
                if ($user->registerUser() == true) {
					$this->regView->setRegSuccess();
                    $this->view->setUserRegistration();
                }
				else
					$this->regView->setRegFail();
			}
		}
		$this->model->renew($userClient);
	}
}