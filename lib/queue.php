<?php 
namespace Chat;
class Queue {

	private static $query = 'SELECT `m`.`created_at`, `m`.`username`, `m`.`message` FROM `messages` AS `m` WHERE `m`.`created_at` > :created_at';

	public function __construct($timestamp , $connect=true) {
		$this->timestamp 		= isset($timestamp) ? $timestamp : date("y/m/d G:i:s");
		if($connect) $this->__connect();
	}

	private function __connect() {	
		include_once("db.php");
		return Database::__connection(); // very simple connection handler
	}

	public function has_new_message() {
		$has_new_messages = $this->checkForNewMessages();

		if($has_new_messages['has_new_messages'] && $messages = $has_new_messages['messages']) {
			$last_message 				 = end($messages);
			$this->response['timestamp'] = $last_message->created_at;
			$this->response['username']	 = $last_message->username;
			return true;
		};
		return false;
	}

	public function checkForNewMessages() {
		$messages 			= $this->get_last_messages();
		$last_message 		= end($messages); // array_pop($messages);

		$has_new_message 	= (isset($last_message) && !empty($messages) && $last_message->created_at != $this->timestamp);

		return $this->response = (array("has_new_messages" => $has_new_message , 'messages' => ($has_new_message ? $messages : array())));
	}

	public function get_last_messages() {
		$record 	= $this->__connect()->prepare(self::$query);
		$record->execute(array(":created_at" => $this->timestamp));

		$messages 	= $record->fetchAll(\PDO::FETCH_OBJ);

		return $messages ;
	}

	public function toJSON() {
		return json_encode($this->response);
	}
}
?>