<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */
class LoginView {
	/**
	 * These names are used in $_POST
	 * @var string
	 */
	private static $login = "LoginView::Login";
	private static $logout = "LoginView::Logout";
	private static $name = "LoginView::UserName";
	private static $password = "LoginView::Password";
	private static $cookieName = "LoginView::CookieName";
	private static $CookiePassword = "LoginView::CookiePassword";
	private static $keep = "LoginView::KeepMeLoggedIn";
	private static $messageId = "LoginView::Message";

	/**
	 * This name is used in session
	 * @var string
	 */
	private static $sessionSaveLocation;
	private static $userSaveLocation;

	/**
	 * view state set by controller through setters
	 * @var boolean
	 */
	private $loginHasFailed = false;
	private $loginHasSucceeded = false;
	private $userDidLogout = false;
	private $userDidRegister = false;
    private $clickedItem = false;

	/**
	 * @var LoginModel
	 */
	private $model;

    /**
     * @var Array of entries
     */
	private $entries;

    /**
     * Variable for the user that is logged in
     */
	private $loggedName;



	/**
	 * @param LoginModel $model
	 */
	public function __construct(LoginModel $model, Entries $entries) {
		self::$sessionSaveLocation = Settings::MESSAGE_SESSION_NAME . Settings::APP_SESSION_NAME;
		self::$userSaveLocation = Settings::USER_SESSION_NAME . Settings::APP_SESSION_NAME;
		$this->model = $model;
		$this->entries = $entries->getEntries();
	}

	/**
	 * accessor method for login attempts
	 * both by cookie and by form
	 * 
	 * @return boolean true if user did try to login
	 */
	public function userWantsToLogin() {
		return isset($_POST[self::$login]) || 
			   isset($_COOKIE[self::$cookieName]);
	}

	/**
	 * Accessor method for logout events
	 * 
	 * @return boolean true if user tried to logout
	 */
	public function userWantsToLogout() {
		return isset($_POST[self::$logout]);	
	}

	/**
	 * Accessor method for login credentials
	 * @return UserCredentials
	 */
	public function getCredentials() {
		return new UserCredentials($this->getUserName(),
									$this->getPassword(),
									$this->getTempPassword(),
									$this->getUserClient());
	}

	public function getUserClient() {
		return new UserClient($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
	}

	/**
	 * Tell the view that login has failed so that it can show correct message
	 *
	 * call this when login has failed
	 */
	public function setLoginFailed() {
		$this->loginHasFailed = true;
	}

	/**
	 * Tell the view that login succeeded so that it can show correct message
	 *
	 * call this if login succeeds
	 */
	public function setLoginSucceeded() {
		$this->loginHasSucceeded = true;
	}

	/**
	 * Tell the view that logout happened so that it can show correct message
	 *
	 * call this when user logged out
	 */
	public function setUserLogout() {
		$this->userDidLogout = true;
	}

    /**
     * Call this when registration was successful
     */

	public function setUserRegistration() {
		$this->userDidRegister = true;
	}

    /**
     * Call this when a menu item was selected
     */

    public function setClickedItem() {
        $this->clickedItem = true;
    }

    public function clickedMenuItem() {
        return $this->clickedItem;
    }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 * @sideeffect Sets cookies!
	 * @return String HTML
	 */
	public function response() {
		if ($this->model->isLoggedIn($this->getUserClient())) {
			return $this->doLogoutForm();
		} else {
			return $this->doLoginForm();
		}
	}

    public function getLogoutButton() {
        return $this->getLogoutButtonHTML();
    }

	/**
	 * @sideeffect Sets cookies!
	 * @return [String HTML
	 */
	private function doLogoutForm() {
		$message = "";
		//Correct Login Message
		if ($this->loginHasSucceeded === true) {
			$message = "Welcome";
			if ($this->rememberMe()) {
				if (isset($_COOKIE[self::$CookiePassword])) {
					$message .= " back with cookie";
				} else {
					$message .= " and you will be remembered";
				}
			}
			$this->redirect($message);
		} else {
			$message = $this->getSessionMessage();
			$this->getSessionUser();
		}

		//Set new cookies
		if ($this->rememberMe()) {
			$this->setNewTemporaryPassword(); 
		} else {
			$this->unsetCookies();
		}

		//generate HTML
		return $this->getMessageHTML($message);
	}

	/**
	 * @sideeffect Sets cookies!
	 * @return [String HTML
	 */
	private function doLoginForm() {
		$message = "";
		//Correct messages
		if ($this->userWantsToLogout() && $this->userDidLogout) {
			$message = "Bye bye!";
			$this->redirect($message);
		} else if ($this->userWantsToLogin() && $this->getTempPassword() != "") {
			$message =  "Wrong information in cookies";
		} else if ($this->userWantsToLogin() && $this->getRequestUserName() == "") {
			$message =  "Username is missing";
		} else if ($this->userWantsToLogin() && $this->getPassword() == "") {
			$message =  "Password is missing";
		} else if ($this->loginHasFailed === true) {
			$message =  "Wrong name or password";
		} else {
			$message = $this->getSessionMessage();
			$this->getSessionUser();
		}

		//cookies
		$this->unsetCookies();
		
		//generate HTML
		return $this->generateLoginFormHTML($message);
	}

	private function redirect($message) {
		$_SESSION[self::$sessionSaveLocation] = $message;
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		header("Location: $actual_link");
	}

	private function getSessionMessage() {
		if (isset($_SESSION[self::$sessionSaveLocation])) {
			$message = $_SESSION[self::$sessionSaveLocation];
			unset($_SESSION[self::$sessionSaveLocation]);
			return $message;
		}
		return "";
	}
	private function getSessionUser() {
		if (isset($_SESSION[self::$userSaveLocation])) {
			$_POST[self::$name] = $_SESSION[self::$userSaveLocation];
			unset($_SESSION[self::$userSaveLocation]);
		}
	}

	/**
	 * unset cookies both locally and on the client
	 */
	private function unsetCookies() {
		setcookie(self::$cookieName, "", time()-1);
		setcookie(self::$CookiePassword, "", time()-1);
		unset($_COOKIE[self::$cookieName]);
		unset($_COOKIE[self::$CookiePassword]);
	}

	private function setNewTemporaryPassword() {

		//set New Cookie
		$tempCred = $this->model->getTempCredentials();
		if ($tempCred) {
			setcookie(self::$cookieName, $this->getUserName(), $tempCred->getExpire());
			setcookie(self::$CookiePassword, $tempCred->getPassword(), $tempCred->getExpire());
		}
	}

    /**
     * Returns HTML code that displays message
     */

    private  function getMessageHTML($message) {
        return "<div class='form-style-2'>
            <form  method='post' >
			<div class='form-style-2-text'>$message</div>
			</form>
			</div>
		";
    }

    /**
     * Returns HTML code that displays logout button
     */

	private function getLogoutButtonHTML() {
        return "<div class='form-style-2'>
            <form  method='post' >
			<input type='submit' name='" . self::$logout . "' value='Logout'/>
			</form>
			</div>
        ";
	}

    /**
     * HTML code that displays login form
     */

	private function generateLoginFormHTML($message) {
        return "<div class='form-style-2'>
			    <div class='form-style-2-heading'>Login - Enter Username and Password</div>
                <form method='post' >
					<div class='form-style-2-message'>$message</div>
					<input type='text' class='input-field' placeholder='Username' id='".self::$name."' name='".self::$name."' value='".$this->getRequestUserName()."'/>
					<input type='password' class='input-field' placeholder='Password' id='".self::$password."' name='".self::$password."'/>
                    <div>
					<label for='".self::$keep."'>
					<input type='checkbox' id='".self::$keep."' name='".self::$keep."'> Keep me logged in
					</label>
                    </div>
					<input type='submit' name='".self::$login."' value='Login'/>
			</form>
			</div>
			</div>
		";
	}

    /**
     * Check if login form is valid
     */

	public function checkForm() {
		return strlen($this->getUserName()) > 0 && strlen($this->getPassword()) > 0;
	}

	private function getRequestUserName() {
		if (isset($_POST[self::$name]))
			return trim($_POST[self::$name]);
		return "";
	}

	public function getUserName() {
		if (isset($_POST[self::$name])) {
			return trim($_POST[self::$name]);
		}

		if (isset($_COOKIE[self::$cookieName]))
			return trim($_COOKIE[self::$cookieName]);
		return "";
	}

	private function getPassword() {
		if (isset($_POST[self::$password]))
			return trim($_POST[self::$password]);
		return "";
	}

	private function getTempPassword() {
		if (isset($_COOKIE[self::$CookiePassword]))
			return trim($_COOKIE[self::$CookiePassword]);
		return "";
	}

	private function rememberMe() {
		return isset($_POST[self::$keep]) || 
			   isset($_COOKIE[self::$CookiePassword]);
	}

	public function getLoggedName() {
		$this->loggedName = "";
		if(isset($_SESSION['username']))
			$this->loggedName = $_SESSION['username'];
		return $this->loggedName;
	}

	public function setSessionName($name) {
		$_SESSION['username'] = $name;
	}

    /**
     * Creates and returns HTML code that displays a list of the users entries
     */

	public function getMenu() {
		$line = "";
		if($this->entries != "") {
			foreach ($this->entries as $entry)  {
                $text = $entry->getText();
				$entryText = explode("\r\n", $text);
				$title = $entryText[0];
				$line .= "<li><a href='?".Settings::VIEW."=".$entry->getID()."'><span>" . $title . "</span></a></li>";
			}
			return "<br>
				<div class='form-style-2-heading'>List of entries</div>
				<div id='cssmenu'>
				<ul>
  					$line
				</ul>
				</div>";
		}
		else
			return "<div class='form-style-2-heading'>List of entries</div>
					<p>No entries found</p>";
	}

    public function userWantsToView() {
        return isset($_GET[Settings::VIEW]);
    }

    /**
     * Compares the selected menu item with entry ID to determine what entry should be displayed
     */
    public function getMenuURL() {
        if(isset($_GET[Settings::VIEW])) {
            $url = $_GET[Settings::VIEW];
            foreach ($this->entries as $entry) {
                if (strcmp($entry->getID(), $url) === 0) {
                    return $entry;
                }
            }
        }
    }


}