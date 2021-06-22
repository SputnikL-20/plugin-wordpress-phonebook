<?php
namespace classes;

class Connect
{
    public function queryMySql($query = null) {
        global $wpdb;
        return $wpdb->get_results($query, ARRAY_A);
    }
}

