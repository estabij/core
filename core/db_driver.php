<?php
/**
 * db driver interface
 *
 * @author estabij
 */
interface db_driver {
    public function query($qry, array $params = array());
    public function addQuery($qry, array $params = array());   
    public function flushQueries();
    public function transaction();
    public function lastID();
    public function getError();
}

/* End of file db_driver.php */
/* Location: ./core/core/db_driver.php */