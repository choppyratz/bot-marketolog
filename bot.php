<?php

class Bot 
{
    public $data;
    public $config;
    private $db;
    public  $api;
    public function  __construct($data, $config, $db, $api) {
        $this->data = $data;
        $this->config = $config;
        $this->db = $db;
        $this->api = $api;
    }

    public function start() {
        switch ($this->data['type']) {
            case 'confirmation':
                return $this->confirm();
                break;
            case 'message_new':
                return $this->newMessage();
                break;
            case 'group_join':
                return $this->joinGroup();
                break;
            case 'group_leave':
                return $this->leaveGroup();
                break;
        }
    }

    public function confirm() {
        return $this->config['bot_config']['confirmation_token'];
    }

    public function newMessage() {
        try {
            $user = new User($this->db);
            $name = $this->api->getUserName($this->data['object']['from_id']);
            if (!$user->inTheGroup($this->data['object']['from_id'])) {
                $this->api->sendMessage('Для работы вам нужно вступить в группу, ' . $name, $this->data['object']['from_id']);
            }else {
                $commandHandler = new CommandHandlers($this->api, $this->data['object']['from_id'], $this->db);
                $message = MHistoryAnalyser::analyse($this->data['object']['text'], $commandHandler);
                if (is_array($message[0])) {
                    foreach ($message[0] as $m) {
                        $this->api->sendMessage($m, $this->data['object']['from_id'], $message[1]);
                    }

                }else {
                    $this->api->sendMessage($message[0], $this->data['object']['from_id'], $message[1]);
                }
            }
        }catch (Exception $e) {
            $this->api->sendMessage('Произошла ошибка', $this->data['object']['user_id']);
        }finally {
            return 'ok';
        }
    }

    public function joinGroup() {
        try 
        {
            $user = new User($this->db);
            $name = $this->api->getUserName($this->data['object']['from_id']);
            $user->insertUser($this->data['object']['user_id']);
            $this->api->sendMessage('Добро пожаловать', $this->data['object']['user_id']);
        }
        catch (Exception $e)
        {
            $this->sendMessage('Произошла ошибка', $this->data['object']['user_id']);
        }
        finally 
        {
            return 'ok';
        }
    }

    public function leaveGroup() {
        try 
        {
            $user = new User($this->db);
            $user->removeUser($this->data['object']['user_id']);
            $this->api->sendMessage('Всего доброго', $this->data['object']['user_id']);
            (new Item($this->db))->removeAllItems($this->data['object']['user_id']);
        }
        catch (Exception $e)
        {
            $this->api->sendMessage('Произошла ошибка', $this->data['object']['user_id']);
        }
        finally 
        {
            return 'ok';
        }
    }
}
