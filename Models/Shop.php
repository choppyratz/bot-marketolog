<?php

class Shop
{
    public  $db;
    public  function __construct($db)
    {
        $this->db = $db;
    }

    public function getShopsList() {
        return $this->db->assoc("SELECT * FROM `shop`");
    }

    public function isSupportedStore($url) {
        if ($this->db->rowsNum("SELECT `id` FROM `shop` WHERE `url`='{$url}'")) {
            return true;
        }else {
            return false;
        }
    }
}
