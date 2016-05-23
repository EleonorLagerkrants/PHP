<?php

/**
 * Created by PhpStorm.
 * User: Eleonor Lagerkrants
 * Date: 2015-09-16
 * Time: 14:42
 */
class UserModel {

    private $name = "Admin";
    private $password = "Password";
    /**
     * Compares the parameters to the $name and $password variables
     *
     * @param $user
     * @param $password
     * @return bool
     */
    public function checkUser($user, $password) {
        if($user == $this->name && $password == $this->password)
            return true;
        else
            return false;
    }

}