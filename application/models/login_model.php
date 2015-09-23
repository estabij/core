<?php
//login model
class login_model extends application_model {

    protected $db;

    public function __construct() {
        parent::__construct('login');
    }

    //get a user given an id
    public function getLastLoginByUser($user_id) {
        $query = "SELECT * from `login` WHERE `user`=? ORDER BY `id` DESC LIMIT 2";
        $result = $this->db->query($query,array($user_id))->result_array();
        if ( $result ) {
            return $result[1]; //we want the last login, not the current one
        } else {
            return false;
        }
    }

    //get all logins
    public function getAll() {
        $query = "SELECT * from `login` ORDER DESC";
        return $this->db->query($query)->result_array();
    }

    //get all logins for a certain user
    public function getAllByUser($user_id) {
        $query = "SELECT * from `login` WHERE `user`=?  ORDER DESC";
        return $this->db->query($query,array($user_id))->result_array();
    }

    // insert a new login
    public function insertLogin($user_id) {

        try {
            $time = date('l jS \of F Y h:i:s A');
            $ip = $_SERVER['REMOTE_ADDR'];
            $query = "INSERT INTO `login` VALUES(NULL, ?, ?, ?)";
            $this->db->query($query, array($user_id, $time, $ip));
        }
        catch (Exception $e) {
            return false;
        }
        return true;
    }
} 