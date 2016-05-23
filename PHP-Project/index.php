<?php
 /**
  * Solution for assignment 2
  * @author Daniel Toll
  */
require_once("Settings.php");
require_once("controller/LoginController.php");
require_once("controller/RegisterController.php");
require_once("controller/EntryController.php");
require_once("controller/ApplicationController.php");
require_once("model/Entries.php");
require_once("view/DateTimeView.php");
require_once("view/LayoutView.php");
require_once("view/RegistrationView.php");
require_once("view/EntryView.php");
require_once("view/ViewEntryView.php");

if (Settings::DISPLAY_ERRORS) {
    error_reporting(-1);
	ini_set('display_errors', 'ON');
}

//session must be started before LoginModel is created
session_start(); 

//Dependency injection
$ed = new EntryDAL();
$m = new LoginModel();
$e = new Entries($ed);
$vev = new ViewEntryView();
$v = new LoginView($m, $e);
$rv = new RegistrationView();
$ev = new EditView();
$rc = new RegisterController($rv, $v);
$ec = new EntryController($ev, $v);
$lc = new LoginController($m, $v, $vev);
$ac = new ApplicationController($lc, $rc, $ec);

//Controller must be run first since state is changed
$ac->doControl();


//Generate output
$dtv = new DateTimeView();
$lv = new LayoutView();
$lv->render($m->isLoggedIn($v->getUserClient()), $v, $dtv, $rv, $ev, $vev);

