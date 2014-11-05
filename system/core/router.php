
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
		$ctrl = $this->getController($url, $uri);
//        var_dump($ctrl);
		if ( $ctrl ) {
			$this->invoke_controller($ctrl);
		} else {
			error_controller::getInstance()->error404();
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

		    include(APPLICATION_PATH.'controllers/'.$ctrl['class'].'.php');

		    call_user_func( array(new $ctrl['class'], $ctrl['method']), $ctrl['params'] );
		    
		} catch (Exception $e) {
		    echo 'Exception: ',  $e->getMessage(), "\n";
		}
	}

	protected function extract_ctrl($_ctrl) {

		$parts = explode("/", $_ctrl);
		$numparts = count($parts);

		if ( $numparts < 2 ) {
			var_dump($parts);
			throw new Exception ( 'Router cannot find controller or method' );
		}

		$ctrl['class']  = $parts[0];
		$ctrl['method'] = $parts[1];

		$ctrl['params'] = array();		
		for ( $i = 2 ; $i < $numparts ; $i++ ) {
			$ctrl['params'][] = $parts[$i]; 	
		}

		return $ctrl; 
	}

	// this method is public so we can easily unit test
	public function getController($url, $uri) {

		$uri = strtolower($uri);

        $routes_string = file_get_contents(APPLICATION_PATH.'config/routes.json');
        if ( $routes_string === FALSE) {
            die('ERROR reading routes file');
        }
        $routes = json_decode($routes_string);

        //static routes
        if (isset($routes->static)) {
            $static = $routes->static;
            foreach ( $static as $pattern => $replacement) {
                if ( strcasecmp($pattern, $uri)==0) {
                    return $this->extract_ctrl($replacement);
                }
            }
        }

        //dynamic (regexp) routes
        if (isset($routes->dynamic)) {
            $dynamic = $routes->dynamic;
            foreach ( $dynamic as $pattern => $replacement ) {
                if ( preg_match($pattern, $uri) ) {
                    return $this->extract_ctrl(preg_replace($pattern, $replacement, $uri));
                }
            }
        }

		return false;
	}
}

/* End of file router.php */
/* Location: ./system/core/router.php */
