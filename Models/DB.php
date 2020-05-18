<?php

class DB 
{
    public $connect;
    public function __construct($config) {
        if ($config['server']['is_local'] == '1') {
            $host = $config['db_config_local']['host'];
            $login = $config['db_config_local']['login'];
            $pass = $config['db_config_local']['password'];
            $dbName = $config['db_config_local']['db_name'];
        }else {
            $host = $config['db_config_prod']['host'];
            $login = $config['db_config_prod']['login'];
            $pass = "|RE2Jd5#Ub|PQ7f0";
            $dbName = $config['db_config_prod']['db_name'];
        }
        $this->connect = mysqli_connect('localhost', 'id6778497_choppyratz', '|RE2Jd5#Ub|PQ7f0', 'id6778497_bot');
    }  

    public function query($query) {
        return mysqli_query($this->connect, $query);
    }

    public function rowsNum($query) {
        return mysqli_num_rows(mysqli_query($this->connect, $query));
    }

    public function assoc($query) {
        $query = mysqli_query($this->connect, $query);
        $result = [];
        $i = 0;
        while (($row = mysqli_fetch_assoc($query))) {
            $result[$i] = $row;
            $i++;
        }
        return $result;
    }
}