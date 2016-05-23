<?php
/**
 * Created by PhpStorm.
 * User: Eleonor Lagerkrants
 * Date: 2015-09-17
 * Time: 13:33
 */
class Session {

    /**
     * See if $_SESSION['username'] is set
     *
     * @return bool
     */
    public function checkSession() {
        if(isset($_SESSION['username']))
            return true;
        else
            return false;
    }

    public function saveSession($username) {
        $_SESSION['username'] = $username;
    }

    public function loadSession() {
        return $_SESSION['username'];
    }
}