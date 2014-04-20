
<?php
/**
 * router
 *
 * @author estabij
 */
require_once(APPLICATION_PATH.'controllers/error_controller.php');

class router {

	private static $_router; 

	// singleton
	public static function getInstance() {
		if ( ! isset(router::$_router) ) {
		  router::$_router = new router();
		}
		return router::$_router;
	}

	// private constructor
	private function __construct() {}

	public function route($url, $uri) {

		$ctrl = $this->scan($url, $uri);
		if ( !$ctrl ) {
			error_controller::getInstance()->error404();
		} else {
			$this->invoke_controller($ctrl);
		}
	}	

	protected function invoke_controller($ctrl) {  
		try {
		    if (!isset($ctrl['class'])) {
		        throw new Exception("Invoke controller has no controller class!");
		    }
		    if (!isset($ctrl['method'])) {
		        throw new Exception("Invoke controller has no controller method");
		    }            
		    if (!isset($ctrl['params'])) {
		        $ctrl['params'] = array();
		    }

		    include(APPLICATION_PATH.'controllers/'.$ctrl['class'].'.php');

		    call_user_func( array(new $ctrl['class'], $ctrl['method']), $ctrl['params'] );
		    
		} catch (Exception $e) {
		    echo 'Exception: ',  $e->getMessage(), "\n";
		}
	}

	protected function extract_ctrl($_ctrl) {
		$parts = explode("/", $_ctrl);
		$numparts = count($parts);
		$params = array();
		$ctrl = array();
		for($i=0;$i<$numparts;$i++) {
			if ($i==0) { $ctrl['class']  = $parts[$i]; 	}
			if ($i==1) { $ctrl['method'] = $parts[$i]; 	}
			if ($i>1)  { $params[$i-2]   = $parts[$i]; 	}
		}
		if ( count($params) ) {
			$ctrl['params'] = $params;
		}
		return $ctrl; 
	}

	protected function scan($url, $uri) {
		
		include(APPLICATION_PATH.'config/routes.php');

		$_uri = $uri['uri'];

		// static routes
		foreach ( $sroute as $pattern => $replacement) {
			if ( strcasecmp($pattern, $_uri)==0) {
				return $this->extract_ctrl($replacement);
			}
		} 

		// regexp routes
		foreach ( $rroute as $pattern => $replacement ) {
			if ( preg_match($pattern, $_uri) ) {
				return $this->extract_ctrl(preg_replace($pattern, $replacement, $_uri)); 
			}
		}

		return false;
	}
}

/* End of file router.php */
/* Location: ./system/core/router.php */
