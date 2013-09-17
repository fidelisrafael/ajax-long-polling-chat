<?php  
namespace Chat;
class Config {
	
	public static function set() {
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
		date_default_timezone_set('America/Sao_Paulo');
	}
	
	// Set constants to connect to database, from anywhere
	public static function databaseConstants() {
		include_once sprintf("%s/%s.php" ,  CONFIG_FOLDER , 'db-settings');
	}
}

?>