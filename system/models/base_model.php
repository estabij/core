<?php
/**
 * base_model
 *
 * @author estabij
 */
require_once(SYSTEM_PATH.'core/driver_factory.php');
class base_model {
    
    protected $driverDB;
    
    public function __construct() {
        include(APPLICATION_PATH.'config/database.php');
        $this->setDB($database);
    }
    
    public function setDB($database) {
         $this->driverDB = driver_factory::create($database);
    }
    
    protected function db() {
        return $this->driverDB;
    }
}
/* End of file base_model.php */
/* Location: ./system/model/base_model.php */
