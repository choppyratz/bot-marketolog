<?php

if (!isset($_REQUEST)) {
    return;
}

require_once('Models/VKApi.php');
require_once('Models/DB.php');
require_once('Models/User.php');
require_once('Models/Item.php');
require_once('Models/MHistoryAnalyser.php');
require_once('Models/CommandHandlers.php');
require_once('Models/Item.php');
require_once('Models/Parser.php');
require_once('Models/Shop.php');
require_once('ShopHtmlAnalyser.php');
require_once ('Models/phpQuery-onefile.php');
require_once('bot.php');

$config = parse_ini_file('config.ini', true);
$data = json_decode(file_get_contents('php://input'), true);

$db = new DB($config);
$api = new VKApi($config);
echo (new Bot($data, $config, $db, $api))->start();
