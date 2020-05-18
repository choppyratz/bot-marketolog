<?php

class CommandHandlers
{
    public $api;
    public $user_id;
    public $db;
    public function __construct($api, $user_id, $db)
    {
        $this->db = $db;
        $this->api = $api;
        $this->user_id = $user_id;
    }

    public function back() {
        return 'Вы в главном меню';
    }

    public function myItems() {
        $items = (new Item($this->db))->getItems($this->user_id);
        if (empty($items)) {
            return 'У вас нет товаров';
        }

        $messages = [];
        for($i = 0; $i < count($items); $i++) {
            $messages[$i] = $items[$i]['id'] . ': ' . $items[$i]['title'] . "\n" . "Цена: " . $items[$i]['price'];
        }
        return $messages;
    }

    public function addItem($link = null) {
        if ($link !== null) {
            $history = $this->api->getMessageHistory(3, $this->user_id);
            foreach ($history as $message) {
                switch ($message['text']) {
                    case 'Введите ссылку на товар':
                        $url_components = parse_url($link);
                        $item = new Item($this->db);
                        $data = (new ShopHtmlAnalyser($this->db))->start($link);

                        if ($data['error'] == 'unknown shop') {
                            return 'Данный сайт не поддерживается';
                        }
                        if (!$item->issetItem($link, $this->user_id)) {
                            $item->insertItem($link, $data['price'], $data['title'], $this->user_id);
                            return 'Товар добавлен';
                        }else {
                            return 'Вы уже добавили данный товар';
                        }
                        break;
                }

            }
        }
        return 'Введите ссылку на товар';
    }

    public function  removeItem($id = null) {
        if ($id !== null) {
            $history = $this->api->getMessageHistory(3, $this->user_id);
            foreach ($history as $message) {
                switch ($message['text']) {
                    case 'Введите номер товара':
                        $item = new Item($this->db);
                        $item->removeItem($id);
                        return 'Товар удален';
                        break;
                }

            }
        }
        return 'Введите номер товара';
    }

    public  function  help() {
        return "'Список магазинов' — Список поддерживаемых магазинов.\n
        'Мои товары' —  список добавленных товаровлде также указаны номера товаров.\n
        'Добавить товар' — позволяет добавить товара ценой которого будет следить бот.Если ссылка введена корректноло бот добавит товар в базу иначе попросит повторить попытку.\n
        'Удалить товар' — позволяет удалить товардля этого необходимо ввести номер товара, который можно найти вфункции 'Мои товары'.\n";
    }

    public function supportedShops() {
        $shops = (new Shop($this->db))->getShopsList();
        $message = "";
        foreach ($shops as $shop) {
            $message .= $shop['url'] . "\n";
        }
        return $message;
    }

}
