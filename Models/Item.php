<?php

class Item
{
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function insertItem($link, $price, $title, $user_id , $id = null) {
        if ($id == null) {
            return $this->db->query("INSERT INTO `items` (`link`, `price`, `title`, `user_id`) VALUES ('{$link}', '{$price}', '{$title}', '{$user_id}')");
        }else {
            return $this->db->query("UPDATE `items` SET `price`='{$price}' WHERE `id`='{$id}'");
        }
    }

    public function removeItem($item_id) {
        return $this->db->query("DELETE FROM `items` WHERE `id`='{$item_id}'");
    }

    public function getItems($user_id) {
        return $this->db->assoc("SELECT * FROM `items` WHERE `user_id`='{$user_id}'");
    }

    public function getAllItems() {
        return $this->db->assoc("SELECT * FROM `items`");
    }

    public function removeAllItems($user_id) {
        return $this->db->query("DELETE FROM `items` WHERE `user_id`='{$user_id}'");
    }

    public function issetItem($link, $user_id) {
        if ($this->db->rowsNum("SELECT `id` FROM `items` WHERE `link` = '{$link}' AND `user_id` = '{$user_id}'")) {
            return true;
        }else {
            return false;
        }
    }
}