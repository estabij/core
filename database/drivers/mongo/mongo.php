<?php

/**
 * mongo driver
 *
 * @author estabij
 */
require_once(SYSTEM_PATH.'core/db_driver.php');

class mongodriver implements db_driver {

    private static $_mongodriver;
    protected $db_config, $link;
    protected $queries = array();

    // singleton
    public static function getInstance($db_config) {
        if ( ! isset(driver_factory::$_mongodriver) ) {
            mongodriver::$_mongodriver = new mongodriver($db_config);
        }
        return mongodriver::$_mongodriver;
    }

    private function __construct($db_config) {
        $this->db_config = $db_config;
    }

    public function connect() {
        if(!empty($this->link)) {
            return $this->link;
        }
        $this->link = new MongoClient();
        $this->link->connect();
        if ($this->link===FALSE) {
            throw new Exception('ERROR: Cannot connect to Mongo');
        }
        return $this->link;
    }

    // disconnect
    public function disconnect() {
        unset($this->link);
    }
    
    public function query($qry, array $params = array()) {
    }
  
    public function prepare($qry, $params) {
    }

    //add queries so you can later commit them all at once in a transaction
    public function addQuery($qry, array $params = array()) {
    }
    
    //empty the queries table
    public function flushQueries() {
    }

    public function transaction() {
    }
    
    public function lastID() {
    }
    
    public function getError() {
    }
}

/* End of file mongo.php */
/* Location: ./core/drivers/mongo.php */
