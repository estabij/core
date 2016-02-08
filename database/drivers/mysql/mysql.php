<?php

/**
 * mysql driver
 *
 * @author estabij
 */
require_once(SYSTEM_PATH.'core/db_driver.php');

class mysqldriver implements db_driver {

    private static $_mysqldriver;
    protected $db_config, $link;
    protected $queries = array();

    public static function getInstance($db_config) {
        if ( ! isset(mysqldriver::$_mysqldriver) ) {
            mysqldriver::$_mysqldriver = new mysqldriver($db_config);
        }
        return mysqldriver::$_mysqldriver;
    }

    private function __construct($db_config) {
        $this->db_config = $db_config;
    }

    private function connect() {
      if(!empty($this->link)) {
         return $this->link;
      }
      $this->link = @mysql_connect($this->db_config->hostname, $this->db_config->username, $this->db_config->password);
      @mysql_select_db($this->db_config->db, $this->link);
        if ($this->link===FALSE) {
            throw new Exception('ERROR: Cannot connect to MySQL');
        }
        return $this->link;
    }

    // disconnect
    public function disconnect() {
      @mysql_close($this->link);
      unset($this->link);
    }
    
    public function query($qry, array $params = array()) {
        $this->connect();
        $result = @mysql_query($this->prepare($qry, $params), $this->link);
        if (!$result) {
            throw new Exception('Invalid query "'.$qry.'" Error: '.$this->getError());
        }
        return new mysql_query_result($result);
    }
  
    public function prepare($qry, $params) {
        if(is_array($params) == false) {
            return $qry;
        }
        $limit = 1;
        foreach($params as $value) {
            if( is_string($value) ) {
               $value = str_replace('?', '&#63;', mysql_real_escape_string($value, $this->link));
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
    
    public function transaction() {
        @mysql_query("BEGIN");
        foreach($this->queries as $query) {
            $result = @mysql_query($query, $this->link);
            if(!$result) {
               @mysql_query("ROLLBACK");
               throw new Exception('Invalid query "'.$query.'" Error: '.$this->getError());
            }
        }
        @mysql_query("COMMIT");
        return true;
    }
    
    public function lastID() {
      return @mysql_insert_id($this->link);
    }
    
    public function getError() {
        return mysql_error();

    }
}

class mysql_query_result {
    
    protected $resultObject;
    
    public function __construct($resultObject) {
        if (is_resource($resultObject)) {
            $this->resultObject = $resultObject;
        } else {
            $this->resultObject = 0;
        }
    }
 
    public function result() {
        if (is_resource($this->resultObject)) {
            $queryResult = array();
            while ($row = @mysql_fetch_object($this->resultObject)) {
                array_push($queryResult, $row);
            }
            return $queryResult; 
        } else {
           return false;
        }  
    }
    
    public function result_array() {
        if (is_resource($this->resultObject)) {
            $queryResult = array();
            while($row = @mysql_fetch_array($this->resultObject, MYSQL_ASSOC)) {
                array_push($queryResult, $row);
            }
            return $queryResult; 
        } else {
           return false;
        }       
    }
    
    public function num_rows() {
        if (is_resource($this->resultObject)) {
           return mysql_num_rows($this->resultObject);
        } else {
           return false;
        }
    }
}
/* End of file mysql.php */
/* Location: ./core/drivers/mysql.php */
