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

        $numParts = count($parts);
        if ( $numParts < 2 ) {
            throw new Exception ('Router cannot handle url');
        } else if ( $numParts == 2 ) {
            $country = $parts[1];
            $domain  = $parts[0];
        } else {
            $country = $parts[$numParts-1];
            $sub     = $parts[0];
            $domain = substr($url, strlen($sub)+1, strlen($url)-strlen($country)-strlen($sub)-2);
        }

		return array('url'=>$url, 'sub'=>$sub, 'domain'=>$domain, 'country'=>$country);
	}

	public function getUri() {
		if ( isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
			if(strpos($uri, "?")!==false) {
				list($uri,) = explode("?", $uri);
			}
			return $uri;
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