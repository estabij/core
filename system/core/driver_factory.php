<?php
/**
 * db driver_factory
 * create a new database driver 
 * given the name of the driver
 *
 * @author estabij
 */
require_once(SYSTEM_PATH.'drivers/mysql.php');
class driver_factory {
    public static function create($database){
        $drivername = array_keys($database)[0];
        if (strcasecmp( $drivername, 'mySQL')==0) {
            return new mysqldriver($database);
        }
        return false;
    }
}
/* End of file driver_factory.php */
/* Location: ./system/core/driver_factory.php */
