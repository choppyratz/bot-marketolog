<?php

class MHistoryAnalyser
{
    public static function analyse($text, $handler) {
        switch ($text) {
            case 'Назад':
                return [$handler->back(), true];
                break;
            case 'Мои товары':
                return [$handler->myItems(), true];
                break;
            case 'Добавить товар':
                return [$handler->addItem(), false];
                break;
            case 'Удалить товар':
                return [$handler->removeItem(), false];
                break;
            case 'Помощь':
                return [$handler->help(), true];
                break;
            case 'Список магазинов':
                return [$handler->supportedShops(), true];
                break;
            default:
                if (preg_match("/^[0-9]+$/", $text)) {
                    return [$handler->removeItem($text), true];
                }

                if (preg_match("/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&%'\(\)\*\+,;=.]+$/", $text)) {
                    return [$handler->addItem($text), true];
                }
                return ['Что-то неизвестное', true];
                break;
        }
    }
}