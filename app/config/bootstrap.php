<?php 
namespace Chat;

class Bootstrap {
	
	private static $defaultController 	= 'index';
	private static $defaultAction 		= 'index';

	public static function init($initApp=true) {
		
		self::commonConstants();
		self::loadFiles();

		if($initApp) {
			$url 	= self::parseURL();
			return self::mapController(sprintf("%s/%s" , $url['controller'] , $url['action']));
		}
		return false;
	}

	public static function parseURL() {
		$not_drop = array('action', 'params');
		$params   = array();
		foreach($_REQUEST as $param => $value) {
			if(!in_array( $param, $not_drop)) {
				$params[$param] = $value;
				unset($_REQUEST[$param]);
			}
		}
		if(!isset($_REQUEST['action'])) $_REQUEST['action'] = self::$defaultAction ;
		
		$_REQUEST['params'] = $params;
		$_REQUEST['action']	= self::toActionMethod($_REQUEST['action']);

		return array("controller" => self::$defaultController , "action" => (isset($_REQUEST['action']) ? $_REQUEST['action'] : self::$defaultAction));
	}

	// very very simple
	public static function toActionMethod($url) {
		$url = preg_replace('/(-|\(|\)|\$|\@|%|\^|&|\*|\!|`|~|\{|\}|\[|\]|\'|\"|:|;)/im', "_", $url) ;
		return $url = preg_replace('/(|\/)/im', "", $url); // remove trailing slashs
	}

	public static function commonConstants() {
		define('APP_FOLDER' 		, ROOT 			. '/app');
		define('LIB_FOLDER' 		, ROOT 			. '/lib');
		define('CONFIG_FOLDER' 		, APP_FOLDER 	. '/config');
		define('CONTROLLERS_FOLDER' , APP_FOLDER 	. '/controllers');
		define('VIEWS_FOLDER' 		, APP_FOLDER 	. '/views');
		define('ENV_DEVELOPMENT'	, preg_match('/127.0.0.1/im' , $_SERVER['SERVER_ADDR']));
	}

	private static function loadFiles() {
		$files_to_autoload = array(
			CONFIG_FOLDER 		. "/config.php",
			CONTROLLERS_FOLDER	. '/base_controller.php'
		);
		foreach($files_to_autoload as $index => $file) 
			include_once $file;

		Config::set();
	}

	private static function mapController($file) {
		$data 			 	= explode("/" , $file)				;
		$controller 	 	= strtolower(array_shift($data)) 	; 
		$action 			= strtolower(array_shift($data))	;

		$controller_name 	= ucfirst($controller) . "Controller";

		include_once(sprintf("%s/%s_controller.php" , CONTROLLERS_FOLDER , $controller));

		$controller_obj 	= new $controller_name($_REQUEST['params'],  $action);
	}

}

?>