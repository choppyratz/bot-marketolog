<?php

//$data = json_decode(file_get_contents('php://input'), true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/Models/DB.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/Models/VKApi.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/Models/Item.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/Models/Shop.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/Models/Parser.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/Models/phpQuery-onefile.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vk_bot/ShopHtmlAnalyser.php');

$config = parse_ini_file($_SERVER['DOCUMENT_ROOT']  . '/vk_bot/config.ini', true);
$db = new DB($config);
$api = new VKApi($config);
$parser = new ShopHtmlAnalyser($db);

$item = new Item($db);
$items = $item->getAllItems();

foreach ($items as $t) {
    $data = $parser->start($t['link']);
    if ((double)$data['price'] > (double)$t['price']) {
        $item->insertItem($t['link'], $data['price'], $t['title'], $t['user_id'], $t['id']);
        $api->sendMessage("Цена на товар {$t['title']} Поднялась.\n Цена: {$data['price']}", $t['user_id']);
    }else if ((double)$data['price'] < (double)$t['price']) {
        $item->insertItem($t['link'], $data['price'], $t['title'], $t['user_id'], $t['id']);
        $api->sendMessage("Цена на товар {$t['title']} Упала.\n Цена: {$data['price']}", $t['user_id']);
    }
}

