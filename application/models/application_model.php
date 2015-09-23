<?php
class application_model extends base_model {

    protected $db, $table;

    public function __construct($table='') {
        $this->db = $this->db_driver('mysqli');
        $this->table = $table;
    }

    public function deleteById($id) {
        $query = "DELETE from `".$this->table."` WHERE `id`=?";
        return $this->db->query($query, array($id));
    }

    public function getById($id) {
        $query = "SELECT * FROM `".$this->table."` WHERE `id`=?";
        return $this->db->query($query, array($id))->result_one();
    }

    public function getAll() {
        $query = "SELECT * FROM `".$this->table."`";
        return $this->db->query($query)->result_array();
    }

    public function getAllSorted($sort='id', $updown='ASC') {
        $query = "SELECT * FROM `".$this->table."` ORDER BY `$sort` $updown";
        return $this->db->query($query)->result_array();
    }

    public function insert($record) {
        $keys = "`id`";
        $qs = 'NULL';
        foreach ( $record as $key => $value ) {
            if (!is_numeric($key)) {
                $keys .= ", `$key`";
                $qs .= ", ?";
            }
        }
        $query = "INSERT INTO `".$this->table."` ( $keys ) VALUES ( $qs )";
        $this->db->query($query, $record);
        return $this->db->lastID();
    }

    public function updateById($record) {
        $sets = '';
        $sep = '';
        $update = array();
        foreach ($record as $key=>$value) {
            if (!is_numeric($key)) {
                if ($key != 'id') {
                    $sets .= $sep;
                    $sets .= "`$key`=?";
                    $sep = ',';
                    $update[$key] = $value;
                }
            }
        }
        $update['id'] = $record['id'];
        $query = "UPDATE `".$this->table."` SET $sets WHERE `id`=?";
        $this->db->query($query, $update);
        return $update['id'];
    }

    public function getFName($name) {
        $unwanted = array(':', '\\', ',', '.', ';', '/');
        $t = str_replace($unwanted, '', strtolower($name));
        return str_replace(' ', '-', $t);
    }

}