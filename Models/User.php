<?php

class User 
{
    private $id;
    private $user_id;
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function inTheGroup($user_id)  {
        if ($this->db->rowsNum("SELECT `id` FROM `user` WHERE `user_id`='{$user_id}'")) {
            return true;
        } else {
            return false;
        }
    }

    public function insertUser($user_id) {
        return $this->db->query("INSERT INTO `user` (`user_id`) VALUES ('{$user_id}')");
    }

    public function removeUser($user_id) {
        return $this->db->query("DELETE FROM `user` WHERE `user_id`='{$user_id}'");
    }
}