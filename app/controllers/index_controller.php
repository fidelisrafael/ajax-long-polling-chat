<?php 
/**
* Simple Polling Chat
*
* This class handle all requests in root URL
* 
* @version 0.1
* @author Rafael Fidelis <rafa_fidelis@yahoo.com.br>
*/
class IndexController extends BaseController {
	
	const MAX_TIME_LIMIT = 60; // set_time_limit or manually

	protected function _setup() {
		include_once(LIB_FOLDER . '/db.php');
		
		if(isset($this->params['truncate'])) {
			Chat\Database::truncate('messages');
		};

		Chat\Database::createTable('messages');

		die("<h3>Looks like everything is fine. Now, gonna work it harder, make it better, do it faster, make us stronger</h3>");
	}

	// render initial screen
	protected function _index() {
		include_once(LIB_FOLDER . "/message.php");
		
		$this->view_assign('messages' , Chat\Message::recentToHTML());

		$this->render('index/index');
	}

	// Ping on server to detected new messages in chat
	protected function _wait_for_messages() {
		if($this->isPOST()) {
			
			include_once LIB_FOLDER . "/queue.php";

			if(!isset($this->params['timestamp'])) $this->params['timestamp'] = date("y/m/d G:i:s");

			$queue = new \Chat\Queue($this->params['timestamp']);
			$timeout = 0;

			while(!$queue->has_new_message() && $timeout < self::MAX_TIME_LIMIT) {
				sleep(1); // wait 1 sec
				$timeout++;
			}

			Chat\Database::close();

			if($timeout >= self::MAX_TIME_LIMIT) {
				$this->JSONOutput(array("messages" => array() , "timeout" => true , "timestamp" => $this->params['timestamp']));
			} else {
				$this->_render($queue->toJSON());
			}

		} else {
			$this->_invalid_request();
		}
	}

	// insert a new message in database (no validations)
	protected function _new_message() {
		
		if($this->isPOST()) {
			include_once LIB_FOLDER . "/message.php";

			$message = new \Chat\Message($this->params);
			$this->JSONOutput(array("success" => $message->save()));
		} else {
			$this->_invalid_request();
		}		
	}

	/* Alias for requests */
	protected function _get_new_messages() {
		return $this->_wait_for_messages();
	}

	protected function _send_message() {
		return $this->_new_message();
	}

	// throw a invalid request error(no need to send headers)
	protected function _invalid_request() {
		return $this->JSONOutput(array("error" => 1 , "message" => "Request method must be POST"));
	}


}

?>