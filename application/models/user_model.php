<?php
//user model
class user_model extends base_model {

    protected $db;

    public function __construct() {
        $this->db = $this->db_driver('mysqli');
    }

    //get a user given an id
    public function getUserById($id) {
        $query = "SELECT * from `user` WHERE `id`='$id' LIMIT 1";
        return $this->db->query($query)->result_one();
    }

    //get a user given an username and password
    public function getUserByUserNameAndPassword($username, $password) {
        $config = config::getInstance();
        $pass_salt = $config->getConfig('pass_salt');
        $password = sha1($password . $pass_salt);

        $query = "SELECT * from `user` WHERE `username`='$username' AND `password`='$password' AND `active`='1' LIMIT 1";
        //syslog(LOG_DEBUG, $query);
        $result = $this->db->query($query)->result_one();
        return $result;
    }

    //get number (0|1) of users given an id
    public function countUsersById($id) {
        $query = "SELECT COUNT(*) AS c from `user` WHERE `id`='$id'";
        $result = $this->db->query($query)->result_one();
        return intval($result['c']);
    }

    //get all names
    public function getAll() {
        $query = "SELECT * from `user`";
        return $this->db->query($query)->result_array();
    }

    // insert a new user (sign up)
    // for username we insert the email address
    public function insertOne($username) {
        $config = config::getInstance();
        $pass_salt = $config->getConfig('pass_salt');

        $password = "someinitialpassword";
        $password = sha1($password . $pass_salt);

        $role = 0;      // role: user=0 (default), 1=admin
        $active = 0;    // active=0 (default)
        $verified = 0;  // verified=0 (default)

        try {
            $query = "INSERT INTO `user` VALUES(NULL, '$username', '$password', $role, $active, $verified)";
            $this->db->query($query);
        }

        catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function getConfirmationLink($email) {

        $h = 'url';

        $query = "SELECT * from `user` WHERE `username`='$email' LIMIT 1";
        $x = $this->db->query($query)->result_one();

        return $h.'/'.$x['id'].'/'.$x['password'];
    }

    public function verifyConfirmationLink($id, $encrypted_password) {
        $query = "SELECT COUNT(*) AS c from `user` WHERE `id`=$id AND `password`='$encrypted_password'";
        $result = $this->db->query($query)->result_one();

        if ( 1 == intval($result['c']) ) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUser($user) {
        $query = "UPDATE `user` SET `username`=?, `role`=?, `active`=?, `verified`=? WHERE `id`=?";
        $param = array($user['username'],$user['role'],$user['active'],$user['verified'],$user['id'] );
        $this->db->query($query, $param);
    }

    public function verifyUser($id) {
        $query = "UPDATE `user` SET `verified` = '1' WHERE `id` =$id";
        $this->db->query($query);
    }

    public function enableUser($id) {
        $query = "UPDATE `user` SET `active` = '1' WHERE `id` =$id";
        $this->db->query($query);
    }

    public function disableUser($id) {
        $query = "UPDATE `user` SET `active` = '0' WHERE `id` =$id";
        $this->db->query($query);
    }

    //remove a user given an id
    public function removeById($id) {
        $query = "DELETE from `user` WHERE `id`='$id'";
        $this->db->query($query);
    }

    public function setPassword($id, $password) {
        $config = config::getInstance();
        $pass_salt = $config->getConfig('pass_salt');
        $password = sha1($password . $pass_salt);
        $query = "UPDATE `user` SET `password` = '$password' WHERE `id` =$id";
        return $this->db->query($query);
    }

} 