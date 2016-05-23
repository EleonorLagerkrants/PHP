<?php

require_once("model/User.php");


class RegistrationView {

    private static $register = "RegisterView::Register";
    private static $message = "RegisterView::Message";
    private static $name = "RegisterView::UserName";
    private static $password = "RegisterView::Password";
    private static $passRepeat = "RegisterView::PasswordRepeat";
    private static $regLink = "register";

    private $regSuccess = false;
    private $regFail = false;

    private static $sessionSaveLocation;
    private static $userSaveLocation;

    public function __construct() {
        self::$sessionSaveLocation = Settings::MESSAGE_SESSION_NAME . Settings::APP_SESSION_NAME;
        self::$userSaveLocation = Settings::USER_SESSION_NAME . Settings::APP_SESSION_NAME;
    }

    private function generateRegistrationForm($message){
        return '<div class="form-style-2">
                <div class="form-style-2-heading">Register new user</div>
                <form action="?register" method="post" enctype="multipart/form-data">
                    <div class="form-style-2-text">Register a new user - Write username and password</div>
                        <div class="form-style-2-message">'.$message.'</div>
                        <input type="text" class="input-field" placeholder="Username" size="20" name="'.self::$name.'" id="'.self::$name.'" value="'.$this->getUsername().'">
                        <br>
                        <input type="password" class="input-field" placeholder="Password" size="20" name="'.self::$password.'" id="'.self::$password.'" value="">
                        <br>
                        <input type="password" class="input-field" placeholder="Repeat Password" size="20" name="'.self::$passRepeat.'" id="'.self::$passRepeat.'" value="">
                        <br>
                        <input id="submit" type="submit" name="'.self::$register.'" value="Register">
                        <br>
                </form>
                </div>';
    }

    public function response() {
        return $this->doRegistrationForm();
    }

    private function doRegistrationForm() {
        $message = "";
        if($this->userWantsToRegister()) {
            if (strlen($this->getUsername()) < 3)
                $message .= "Username has too few characters, at least 3 characters.";
            if ($this->getPassword() !== $this->getPassRepeat())
                $message .= "Passwords do not match.";
            if (strlen($this->getPassword()) < 6)
                $message .= "Password has too few characters, at least 6 characters.";
            if (!ctype_alnum($this->getUsername())){
                $message .= "Username contains invalid characters.";
                $this->removeHTML();
            }
            if ($this->regFail)
                $message = "User exists, pick another username.";
        }
        return $this->generateRegistrationForm($message);
    }

    public function userWantsToRegister() {
        return isset($_POST[self::$register]);
    }

    public function clickedRegister() {
        return isset($_GET[self::$regLink]);
    }

    public function getRegLink() {
        return '<a href="?'.self::$regLink.'">Register a new user</a>';
    }

    public function getLoginLink() {
        return '<a href="?">Back to login</a>';
    }

    /**
     * Method that controls if the input values are valid.
     *
     */
    public function checkForm() {
        return strlen($this->getUsername()) > 2 && strlen($this->getPassword()) > 5
        && ($this->getPassword() === $this->getPassRepeat()) && ctype_alnum($this->getUsername());
    }

    /**
     * If registration is successful this method is called from the controller
     */
    public function setRegSuccess() {
        $_SESSION[self::$sessionSaveLocation] = "Registered new user.";
        $_SESSION[self::$userSaveLocation] = $this->getUsername();
        unset($_GET[self::$regLink]);
        $this->regSuccess = true;
        $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        header("Location: $actual_link");
        exit;
    }

    public function setRegFail() {
        $this->regFail = true;
    }

    public function regSuccess() {
        return $this->regSuccess;
    }

    private function getUsername() {
        if(isset($_POST[self::$name])) {
            return trim($_POST[self::$name]);
        }
        return "";
    }

    private function getPassword() {
        if(isset($_POST[self::$password])) {
            return trim($_POST[self::$password]);
        }
        return "";
    }

    private function getPassRepeat() {
        if(isset($_POST[self::$passRepeat])) {
            return trim($_POST[self::$passRepeat]);
        }
        return "";
    }

    public function getUser() {
        return new User($this->getUsername(), $this->getPassword());
    }

    /**
     * Method used to strip the username of html tags
     */
    public function removeHTML() {
        $name = $this->getUsername();
        $_POST[self::$name] = strip_tags($name);
    }

}