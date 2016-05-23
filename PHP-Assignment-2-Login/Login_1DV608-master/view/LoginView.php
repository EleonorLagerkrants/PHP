<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	/**
	 * Constructor
	 *
	 * Creates instance of a User and a Session
	 *
	 * @param $u
	 * @param $s
	 */
	public function __construct($u, $s) {
		$this->UserModel = $u;
		$this->Session = $s;
	}
	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = "";

		//check if login button pressed and then if the correct username and password has been entered
		if ($this->getLogin()) {
			if ($this->getRequestUserName() == "")
				$message = "Username is missing";
			elseif ($this->getRequestPassword() == "") {
				$message = "Password is missing";
			}
			else {
				if (!$this->UserModel->checkUser($this->getRequestUserName(), $this->getRequestPassword()))
					$message = "Wrong name or password";
				else {
					$_SESSION['Logged'] = true;
					$response = $this->generateLogoutButtonHTML($message);
				}
			}
		}
		//check if logout button is pressed and destroy session if true
		elseif($this->getLogout()) {
			if ($_SESSION['Logged'] == true)
				$message = "Bye bye!";
			session_destroy();
			$_SESSION['Logged'] = false;
			$response = $this->generateLoginFormHTML($message);
		}
		else{
			$message = "";
			$response = $this->generateLogoutButtonHTML($message);
		}

		//check if user is logged in
		if ($_SESSION['Logged'] == true) {

			//if there is a saved session no message should be displayed
			if ($this->checkSession())
				$response = $this->generateLogoutButtonHTML($message);

			//if there is no session saved, save session and display message
			else {
				$message = "Welcome";
				$this->saveSession($this->getRequestUserName());
				$response = $this->generateLogoutButtonHTML($message);
			}
		}
		else {
			$response = $this->generateLoginFormHTML($message);
		}
		return $response;
	}
	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	 * Generate HTML code on the output buffer for the logout button
	 * @param $message, String output message
	 * @return  void, BUT writes to standard output!
	 */
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() .'" />
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	/**
	 * Return username input
	 *
	 * @return string
	 */
	public function getRequestUserName() {
		if(isset($_POST[self::$name]))
			$username = $_POST[self::$name];
		else
			$username = "";
		return $username;
	}

	/**
	 * Return password input
	 *
	 * @return string
	 */
	public function getRequestPassword() {
		if (isset($_POST[self::$password]))
			$password = $_POST[self::$password];
		else
			$password = "";
		return $password;
	}

	/**
	 * Check if Login has been pressed
	 *
	 * @return bool
	 */
	private function getLogin() {
		return (isset($_POST[self::$login]));
	}

	/**
	 * Check if Lohout has been pressed
	 *
	 * @return bool
	 */
	public function getLogout() {
		return (isset($_POST[self::$logout]));
	}

	/**
	 * Saves session
	 *
	 * @param $username
	 */
	private function saveSession($username) {
		$this->Session->saveSession($username);
	}

	/**
	 * Calls checkSession() in Session class
	 *
	 * @return mixed
	 */
	private function checkSession() {
		return $this->Session->checkSession();
	}
}