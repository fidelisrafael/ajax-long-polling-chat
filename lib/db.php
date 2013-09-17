<?php  
namespace Chat;
class Database { 

	private static $connection ;
	private static $configFileLoaded = false;

	public static function __connection() {
		if(!self::$connection) {
			self::loadConfigFile();
			try  {
				self::$connection = new \PDO(sprintf("mysql:host=%s;dbname=%s", DB_HOST , DB_NAME),  DB_USER, DB_PASS);
			} catch (\PDOException $e) { 
				self::setupInstructions();
			}
		}
		return self::$connection;
	}

	public static function createDatabase($dbName) {
		return self::createSQL('database' , $dbName);
	}

	// this function simple loads a ".sql" file and execute it to create tables(the easiest way is to export via phpmyadmin)
	public static function createTable($tableName) {
		
		return self::createSQL('table' , $tableName);
	}
	public static function truncate($table) { 

		return self::runArbitrarySQL("TRUNCATE TABLE $table");
	}

	public static function createAppDB() {
		self::loadConfigFile();
		$sql_filename 	= sprintf("%s/%s.sql" , MIGRATIONS_FOLDER , 'createSimple_chat');
		$cmd = sprintf('mysql -u %s -p%s < %s' ,DB_USER,DB_PASS, $sql_filename);

		return exec($cmd);
	}

	public static function close() {
		self::$connection = NULL;
	}

	private static function getConfigFileName() {
		return (sprintf("%s/%s-%s.php" , __DIR__ , "/../app/config/db/db-config" , defined('ENV_DEVELOPMENT') && ENV_DEVELOPMENT ? "development" : 'production'));
	}

	private static function loadConfigFile() {
		if(self::$configFileLoaded) return false ;
		include self::getConfigFileName();
		self::$configFileLoaded = true;
	}

	private static function runArbitrarySQL($sql) {
		$result =  self::__connection()->exec($sql) || self::__connection()->query($sql);
		self::close();
		return $result;
	}

	// create tables and databases
	private function createSQL($type, $value , $check=false) {
		$queries = array("table" => "SHOW tables LIKE ?" , "database" => "SHOW tables LIKE ?" );

		$query 		= self::__connection()->prepare($queries[$type]);
		$query->bindParam(1 , $value);

		$resourceExists  = ($query->rowCount() == 0);

		if($check) return $resourceExists;

		// resource don't exists
		if($resourceExists) {
			$migrationFile 	  	= sprintf("%s/create%s.sql", MIGRATIONS_FOLDER , ucfirst($value));
			
			ob_start();			
			include($migrationFile);
			$sql 				= ob_get_clean();
			// try to create the table
			$result = self::runArbitrarySQL($sql);

			self::close();

			return $result;
		}
	}

	protected function setupInstructions() {
		$sql_filename 	= sprintf("%s/%s.sql", MIGRATIONS_FOLDER , "createSimple_chat");
		$cmd 			= sprintf('mysql -u %s -p%s < %s' ,"[YOU USERNAME]","[YOUR PASSWORD]", $sql_filename);
		die("<h2>Please, create the database `" . DB_NAME . "` for application.</h2><h5>You can import the file {$sql_filename} using phpMyAdmin/mysqldump	 or run : </h5><pre>{$cmd}</pre><h3> Now finish, and <a href='setup' title='create tables'>create database tables</a></h3>");
	}
}

?>