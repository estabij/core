<?php
/**
 * base_model
 *
 * @author Erik Stabij
 * @version 0.10
 * @package core
 */
require_once(SYSTEM_PATH.'core/driver_factory.php');
class base_model {

    protected function db_driver($driver_name) {
        return driver_factory::getInstance()->getDriver($driver_name);
    }
}
/* End of file base_model.php */
/* Location: ./core/model/base_model.php */
