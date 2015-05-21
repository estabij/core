
<?php

class config {

	private static $_config;
    private $configurations = false;

	// singleton
	public static function getInstance() {
		if ( ! isset(config::$_config) ) {
            config::$_config = new config();
		}
		return config::$_config;
	}

	// private constructor
	private function __construct() {}

    //get the value for a certain key or false of not found
	public function getConfig($givenkey) {

        if (! $this->configurations) {
            $configurations_string = file_get_contents(APPLICATION_PATH.'config/config.json');
            if ( $configurations_string === FALSE) {
                die('ERROR reading config.json file');
            }
            $this->configurations = json_decode($configurations_string);
        }

        foreach ( $this->configurations as $key => $value) {
            if ( strcasecmp($key, $givenkey)==0) {
                return $value;
            }
        }

		return false;
	}
}

/* End of file config.php */
/* Location: ./system/core/config.php */
