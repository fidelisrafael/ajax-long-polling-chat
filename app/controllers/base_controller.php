<?php 
/**
* Simple Polling Chat
*
* This class is responsible to handle templates renders and get data from view and pass it to 
* another controllers.
* 
* @version 0.1
* @author Rafael Fidelis <rafa_fidelis@yahoo.com.br>
*/


class BaseController {
	
	public $params 		;
	public $action    ;
  public $response 	;

	protected $view_params  = array()       ;   /* View params */

  public function __construct($params , $action) {

 		$this->params         = $params;

    $this->request_method = $_SERVER['REQUEST_METHOD'];

   	/* Callback to be called in all others classes to setup things :D */
   	$this->_init();

   	/* Insert dash to prevent erros with functions names (eg : new - reserved word) */
   	$action = $this->action_name    = "_" . $action;
    
    if(method_exists($this,$action)) {
     	// call the action , process the data and render the view :)
     	$this->response = $this->$action();
    } else {
      $this->__notFound();
    }
 	}

	// Function called after controller creation (such as before_filter)
	public function _init() { 
  }
  
  public function redirect($path, $message=array()) {
    @ob_start();
    header("Location: $path");
  }

  protected function render($filename, $layout=true) {
    $this->view_html   = $this->renderHTML($filename);
    echo $layout ? $this->renderLayout() :  $this->view_html;
  }

  private function renderHTML($filename) {
    ob_start();
    $params = $this->view_params;
    extract($params , false); // easiest way to access params
    include_once($this->getViewFullPath($filename));
    return ob_get_clean();
  }

  private function renderLayout() {
    return $this->renderHTML("layout/layout");
  }

  private function getViewFullPath($filename) {
    return (sprintf("%s/%s.php" , VIEWS_FOLDER , $filename));
  }
  
  protected function JSONOutput($response) {
    print_r(json_encode($response));
  }
  protected function _render($response) {
    print_r($response) && exit();
  }
  protected function isPOST() {
    return ($this->request_method == "POST");
  }
  protected function isGET() {
    return ($this->request_method == "GET");
  }

  protected function __notFound() {
    header('HTTP/1.0 404 Not Found');
    die(ENV_DEVELOPMENT ? 
        "<pre>Method {$this->action_name} not found in " . get_called_class() . "</pre>" : 
        "<h1>404 not found</h1> This page could not be found."
    );
  }
  
  protected function view_assign($key,$value) {
           return $this->view_params[$key] = $value;
   }

}

?>