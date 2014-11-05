<?php

/**
 * postgres driver
 *
 * @author estabij
 */
require_once(SYSTEM_PATH.'core/db_driver.php');

class postgresdriver implements db_driver {

    private static $_postgresdriver;
    protected $db_config, $link;
    protected $queries = array();

    public static function getInstance($db_config) {
        if ( ! isset(postgresdriver::$_postgresdriver) ) {
            postgresdriver::$_postgresdriver = new postgresdriver($db_config);
        }
        return postgresdriver::$_postgresdriver;
    }

    private function __construct($db_config) {
        $this->db_config = $db_config;
    }

    protected function connect_string() {
        $connect_string = "";
        if (isset($this->db_config->hostname) && $this->db_config->hostname != '') {
            $connect_string .= "host=".$this->db_config->hostname;
        }
        if (isset($this->db_config->port) && $this->db_config->port != '') {
            $connect_string .= " port=".$this->db_config->port;
        }
        if (isset($this->db_config->db) && $this->db_config->db != '') {
            $connect_string .= " dbname=".$this->db_config->db;
        }
        if (isset($this->db_config->username) && $this->db_config->username != '') {
            $connect_string .= " user=".$this->db_config->username;
        }
        if (isset($this->db_config->password) && $this->db_config->password != '') {
            $connect_string .= " password=".$this->db_config->password;
        }
        return $connect_string;
    }

    public function connect() {
        if(!empty($this->link)) {
            return $this->link;
        }
        $this->link = @pg_connect($this->connect_string());
        if ($this->link===FALSE) {
            throw new Exception('ERROR: Cannot connect to Postgres');
        }
        return $this->link;
    }

    // disconnect
    public function disconnect() {
        pg_close($this->link);
        unset($this->link);
    }
    
    public function query($qry, array $params = array()) {
        $this->connect();

        $result = @pg_query($this->link, $this->prepare($qry, $params));

        if (!$result) {
            throw new Exception('Invalid query "'.$qry.'" Error: '.$this->getError());
        }
        return new pg_query_result($result);
    }
  
    public function prepare($qry, $params) {
        if(is_array($params) == false) {
            return $qry;
        }
        $limit = 1;
        foreach($params as $value) {
            if( is_string($value) ) {
                $value = str_replace('?', '&#63;', pg_escape_string($this->link, $value));
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
        @pg_query($this->link, "BEGIN");
        foreach($this->queries as $query) {
            $result = @pg_query($this->link, $query);
            if(!$result) {
                @pg_query($this->link, "ROLLBACK");
                throw new Exception('Invalid query "'.$query.'" Error: '.$this->getError());
            }
        }
        @pg_query($this->link, "COMMIT");
        return true;
    }
    
    public function lastID() {
        $insert_query = @pg_query("SELECT lastval();");
        $insert_row = @pg_fetch_row($insert_query);
        $insert_id = $insert_row[0];
    }
    
    public function getError() {
        return @pg_last_error();
    }
}

class pg_query_result {

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
            while ($row = @pg_fetch_object($this->resultObject)) {
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
            while($row = @pg_fetch_array($this->resultObject, null, PGSQL_ASSOC)) {
                array_push($queryResult, $row);
            }
            return $queryResult;
        } else {
            return false;
        }
    }

    public function num_rows() {
        if (is_resource($this->resultObject)) {
            return @pg_num_rows($this->resultObject);
        } else {
            return false;
        }
    }
}
/* End of file postgres.php */
/* Location: ./system/drivers/postgres.php */
