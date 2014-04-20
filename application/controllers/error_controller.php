
<?php
/*
 * error controller 
 * @author estabij
 *
 */
class error_controller {

	private static $_error_controller;

	// singleton
	public static function getInstance() {
		if ( ! isset(error_controller::$_error_controller) ) {
		  error_controller::$_error_controller = new error_controller();
		}
		return error_controller::$_error_controller;
	}

	// private constructor
	private function __construct() {}

	public function error404() {
		echo 'error 404 - page not found'.PHP_EOL;
	}
}
/* End of file error_controller.php */
/* Location: ./application/controllers/error_controller.php */

