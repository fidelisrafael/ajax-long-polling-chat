<?php  
namespace Chat;
class Message { 
	
	public function __construct($data) {
		$this->data 	= $data;
		$this->__connect();
	}

	public function save() {
		$message 	= self::__connect()->prepare("INSERT INTO `messages` (`id`, `username`, `message`, `created_at`) VALUES (:id,:username , :message, :timestamp)");

		$data 		= array (
			":id"			=> NULL,
			":username" 	=> $this->data['username'] ,
			":message" 		=> $this->data['message'],
			":timestamp"	=> date("y/m/d G:i:s")
		);

		$result = $message->execute($data) ;
		Database::close();

		return $result ;
	}

	private static function __connect() {
		include_once("db.php");
		return Database::__connection(); // very simple connection handler
	}

	public static function recent() {
		$messages = self::__connect()->query("SELECT * FROM `messages` ORDER BY `id` DESC LIMIT 10")->fetchAll(\PDO::FETCH_OBJ);
		$messages = array_reverse($messages);

		Database::close();

		return $messages;
	}

	public static function recentToHTML() {
		$html 			= '';
		$base_message 	= "<li><span class='message-date icon time'>&nbsp;%s&nbsp; | &nbsp;</span><span class='message-owner icon user'>&nbsp;%s</span><span class='message'>%s</span></li>";
		// loooooooooop
		foreach(self::recent() as $message):
			$html .= sprintf($base_message , strftime("%d/%m/%Y %T" , strtotime($message->created_at)) , $message->username , $message->message);
		endforeach;

		return $html;
	}
}

?>
