<?php

/**
 * mysql driver
 *
 * @author estabij
 */
require_once(SYSTEM_PATH.'core/db_driver.php');

class mysqlidriver implements db_driver {

    private static $_mysqlidriver;
    protected $db_config, $mysqli;
    protected $queries = array();

    public static function getInstance($db_config) {
        if ( ! isset(mysqlidriver::$_mysqlidriver) ) {
            mysqlidriver::$_mysqlidriver = new mysqlidriver($db_config);
        }
        return mysqlidriver::$_mysqlidriver;
    }

    private function __construct($db_config) {
        $this->db_config = $db_config;
    }

    private function connect() {
        if(!empty($this->mysqli)) {
            return $this->mysqli;
        }
        $this->mysqli = new mysqli(
                            $this->db_config->hostname,
                            $this->db_config->username,
                            $this->db_config->password,
                            $this->db_config->db );
        if ($this->mysqli->connect_errno) {
            throw new Exception("Connect failed: %s\n", $this->mysqli->connect_error);
        }
        return $this->mysqli;
    }

    // disconnect
    public function disconnect() {
        $this->mysqli->close();
    }
    
    public function query($qry, array $params = array()) {
        $this->connect();
        $result = $this->mysqli->query($this->prepare($qry, $params));
        if (!$result) {
            throw new Exception('Invalid query "'.$qry.'" Error: '.$this->mysqli->error);
        }
        return new mysqli_query_result($result);
    }

    public function prepare($qry, $params) {
        if(is_array($params) == false) {
            return $qry;
        }
        $limit = 1;
        foreach($params as $value) {
            if( is_string($value) ) {
               $value = str_replace('?', '&#63;', $this->mysqli->real_escape_string($value));
               $qry = preg_replace('/\?/', "'" . $value . "'", $qry, $limit);
            } else {
                $qry = preg_replace('/\?/', $value, $qry, $limit);
            }
        }
        return $qry;
    }

    //add queries so you can later commit them all at once in a transaction
    public function addQuery($qry, array $params = array()) {
        $this->queries[] = $this->prepare($qry, $params);
    }
    
    //empty the queries table
    public function flushQueries() {
        $this->queries = array();
    }

    //TODO: replace with multi_query - http://php.net/manual/en/mysqli.multi-query.php
    public function transaction() {
        $this->mysqli->autocommit(FALSE);
        foreach($this->queries as $query) {
            $result =  $this->mysqli->query($this->queries);
            if(!$result) {
                $this->mysqli->rollback();
                throw new Exception('Invalid query "'.$query.'" Error: '.$this->mysqli->error);
            }
        }
        if (!$this->mysqli->commit()) {
            throw new Exception("Transaction commit failed. Error: ".$this->mysqli->error);
        };
        return true;
    }
    
    public function lastID() {
      return $this->mysqli->insert_id;
    }
    
    public function getError() {
        return $this->mysqli->error;
    }

}

class mysqli_query_result {
    
    protected $resultObject;
    
    public function __construct($resultObject) {
        if (is_object($resultObject)) {
            $this->resultObject = $resultObject;
        } else {
           $this->resultObject = 0;
        }
    }
 
    public function result() {
        if (is_object($this->resultObject)) {
            $queryResult = array();
            while ($row = $this->resultObject->fetch_object()) {
                array_push($queryResult, $row);
            }
            return $queryResult; 
        } else {
           return false;
        }  
    }
    
    public function result_array() {
        if (is_object($this->resultObject)) {
            $queryResult = array();
            while($row = $this->resultObject->fetch_array()) {
                array_push($queryResult, $row);
            }
            return $queryResult; 
        } else {
           return false;
        }       
    }
    
    public function num_rows() {
        if (is_object($this->resultObject)) {
           return $this->resultObject->num_rows();
        } else {
           return false;
        }
    }

    public function free() {
        if (is_object($this->resultObject)) {
            $this->resultObject->free();
        }
    }
}
/* End of file mysql.php */
/* Location: ./system/drivers/mysql.php */
