<?php  
/**
* Simple Polling Chat
*
* This class bootstrap the chat app :)
* 
* @version 0.1
* @author Rafael Fidelis <rafa_fidelis@yahoo.com.br>
*/

namespace Chat;
class Initializer {
	// We are human after all, flesh in common after all
	public static function hum4n4f73r4ll() {

		/* Constants */
		define("ROOT" , __DIR__); 
		
		// Include bootstrap file \o/
		require_once ROOT . "/app/config/bootstrap.php";

		// Bootstrap the app
		Bootstrap::init();
	}
}

Initializer::hum4n4f73r4ll();

?>