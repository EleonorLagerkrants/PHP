<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */
/**
 * The settings file contains installation specific information
 * 
 */
class Settings {


	/**
	 * The app session name allows different apps on the same webhotel to share a virtual session
	 */
	const APP_SESSION_NAME = "Project";
	const MESSAGE_SESSION_NAME = "Message::";
	const USER_SESSION_NAME = "User::";

	/**
	 * Username of default user
	 */
	const USERNAME = "Admin";

	/**
	 * Password of default user
	 */
	const PASSWORD = "Password";

	/**
	 * Path to folder writable by www-data but not accessable by webserver
	 */
	const DATAPATH = "./data/";

	/**
	 * Path to folder where users are stored
	 */
	const USERPATH = "./Database/";

	/**
	 * Path to folder where entries are stored
	 */
	const ENTRYPATH = "./Entry/";
	/**
	 * Salt for creating temporary passwords
	 * Should be a random string like "feje3-#GS"
	 */
	const SALT = "feje3-#GS";

	const VIEW = "view";

	/**
	 * Show errors 
	 * boolean true | false
	 */
	const DISPLAY_ERRORS = true;

}