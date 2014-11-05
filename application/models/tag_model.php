<?php

//tag model
class tag_model extends base_model {

    protected $db;

    public function __construct() {
        $this->db = $this->db_driver('mysqli');
    }

    //get the tag given a tag id
    public function get($id) {
        $qry = 'SELECT * from tbltags WHERE id=?';
        return $this->db->query($qry, array( $id ))->result();
    }

    //get all tags
    public function getAll() {
        $qry = 'SELECT * FROM tbltags WHERE 1';
        return $this->db->query($qry)->result();
    }
} 