<?php
/**
 * db driver_factory
 * create a new database driver 
 * given the name of the driver
 *
 * @author estabij
 */

//drivers that the driver_factory supports:
require_once(SYSTEM_PATH.'database/drivers/mysql/mysql.php');
require_once(SYSTEM_PATH.'database/drivers/mysqli/mysqli.php');
require_once(SYSTEM_PATH.'database/drivers/postgres/postgres.php');
require_once(SYSTEM_PATH.'database/drivers/mongo/mongo.php');

//singleton database drive factory class responsible for returning database driver instances
class driver_factory {

    private static $_driver_factory;
    protected $db;

    // singleton
    public static function getInstance() {
        if ( ! isset(driver_factory::$_driver_factory) ) {
            driver_factory::$_driver_factory = new driver_factory();
        }
        return driver_factory::$_driver_factory;
    }

    private function __construct() {
        $myConfig = file_get_contents(APPLICATION_PATH.'config/database.json');
        if ( $myConfig === FALSE) {
            throw new Exception('ERROR: Cannot read database.json');
        }
        $this->database_config = json_decode($myConfig);
    }

    public function getDriver($driver_name){
        switch ($driver_name) {
            case 'postgres':
                return postgresdriver::getInstance($this->database_config->postgres);
                break;
            case 'mysql':
                return mysqldriver::getInstance($this->database_config->mysql);
                break;
            case 'mysqli':
                return mysqlidriver::getInstance($this->database_config->mysqli);
                break;
            case 'mongo':
                return mongodriver::getInstance($this->database_config->mongo);
                break;
        }
        throw new Exception('ERROR: Unsupported database driver '.$driver_name);
    }
}
/* End of file driver_factory.php */
/* Location: ./core/core/driver_factory.php */
