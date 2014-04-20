<?php
/**
 * front end controller
 *
 * @author estabij
 */

require_once(SYSTEM_PATH.'core/router.php');

class front_end_controller {

	private static $_front_end_controller; 

	// singleton
	public static function getInstance() {
		if ( ! isset(front_end_controller::$_front_end_controller) ) {
		  front_end_controller::$_front_end_controller = new front_end_controller();
		}
		return front_end_controller::$_front_end_controller;
	}

	// private constructor
	private function __construct() {}

	public function getUrl() {
		if ( isset($_SERVER['HTTP_HOST'])) {
			$url = $_SERVER['HTTP_HOST'];
		} else {
			return false;
		}

		$parts = explode(".", $url);

		switch ( count($parts) ) {
			case 2:
				$sub 		= '';	
				$domain 	= $parts[0];	
				$country 	= $parts[1]; 

			break;
			case 3:
				$sub 		= $parts[0];	
				$domain 	= $parts[1];	
				$country 	= $parts[2];	
			break;
			default:
				throw new Exception ('Router cannot handle url');
		}

		return array('url'=>$url, 'sub'=>$sub, 'domain'=>$domain, 'country'=>$country);
	}

	public function getUri() {
		if ( isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
			if(strpos($uri, "?")!==false) {
				list($uri,) = explode("?", $uri);
			}
			$uri_parts1 = explode("/", $uri);

			$uri_parts = array();
			foreach($uri_parts1 as $uri_part) {
				if ( !empty($uri_part) ) {
					$uri_parts[] = $uri_part;
				}
			}			
			return array('uri'=>$uri, 'uri_parts'=>$uri_parts);
		} else {
			return false;
		}
	}

	public function run() {
		router::getInstance()->route($this->getUrl(), $this->getUri());
	}
}
/* End of file front_end_controller.php */
/* Location: ./system/controllers/front_end_controller.php */
