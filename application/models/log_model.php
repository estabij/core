<?php

class log_model extends application_model {

    protected $db;

    public function __construct() {
        parent::__construct('log');
    }
}