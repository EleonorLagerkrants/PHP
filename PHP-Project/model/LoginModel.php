<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */

require_once("model/UserCredentials.php");
require_once("model/TempCredentials.php");
require_once("model/TempCredentialsDAL.php");
require_once("model/LoggedInUser.php");
require_once("model/UserClient.php");
require_once("model/UserDAL.php");
require_once("model/EntryDAL.php");



class LoginModel {

	//TODO: Remove static to enable several sessions
	private static $sessionUserLocation = "LoginModel::loggedInUser";

	/**
	 * @var null | TempCredentials
	 */
	private $tempCredentials = null;
	private $user;

	private $tempDAL;
	private $userDAL;
    private $entryDAL;

	public function __construct() {
		self::$sessionUserLocation .= Settings::APP_SESSION_NAME;

		if (!isset($_SESSION)) {
			//Alternate check with newer PHP
			//if (\session_status() == PHP_SESSION_NONE) {
			assert("No session started");
		}
		$this->tempDAL = new TempCredentialsDAL();
		$this->userDAL = new UserDAL();
        $this->entryDAL = new EntryDAL();
		
	}

	/**
	 * Checks if user is logged in
	 * @param  UserClient $userClient The current calls Client
	 * @return boolean                true if user is logged in.
	 */
	public function isLoggedIn(UserClient $userClient) {
		if (isset($_SESSION[self::$sessionUserLocation])) {
			$user = $_SESSION[self::$sessionUserLocation];

			if ($user->sameAsLastTime($userClient) == false) {
				return false;
			}
			return true;
		} 

		return false;
	}

	/**
	 * Attempts to authenticate
	 * @param  UserCredentials $uc
	 * @return boolean
	 */
	public function doLogin(UserCredentials $uc) {

			$this->tempCredentials = $this->tempDAL->load($uc->getName());
			$password = $this->userDAL->load($uc->getName());

			$loginByUsernameAndPassword = $uc->getName() !== null && $password === $uc->getPassword();
			$loginByTemporaryCredentials = $this->tempCredentials != null && $this->tempCredentials->isValid($uc->getTempPassword());
			$loginAsAdmin = $uc->getName() === Settings::USERNAME && $uc->getPassword() === Settings::PASSWORD;

			if ($loginAsAdmin || $loginByUsernameAndPassword || $loginByTemporaryCredentials ) {
				$user = new LoggedInUser($uc);

				$_SESSION[self::$sessionUserLocation] = $user;

				return true;
			}
			return false;
	}

	public function doLogout() {
		unset($_SESSION[self::$sessionUserLocation]);
	}

	/**
	 * @return TempCredentials
	 */
	public function getTempCredentials() {
		return $this->tempCredentials;
	}

	/**
	 * renew the temporary credentials
	 * 
	 * @param  UserClient $userClient 
	 */
	public function renew(UserClient $userClient) {
		if ($this->isLoggedIn($userClient)) {
			$user = $_SESSION[self::$sessionUserLocation];
			$this->tempCredentials = new TempCredentials($user);
			$this->tempDAL->save($user, $this->tempCredentials);
		}
	}

	public function loadEntries($name, $ID) {
        $text = $this->entryDAL->load($name, $ID);
        return $text;
    }

	public function redirectViewEntry($entryID) {
        header("Location: $entryID");

	}
	
}