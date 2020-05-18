<?php

class VKApi
{
    public $config;
    public  function __construct($config)
    {
        $this->config = $config;
    }

    public function apiCall($method, $params = []) {
        $params['access_token'] = $this->config['bot_config']['token'];
        $params['v'] = $this->config['bot_config']['api_version'];

        $query = http_build_query($params);
        $url = $this->config['bot_config']['api_endpoint'] . $method . '?' . $query;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }

    public function sendMessage($message, $user_id, $main_keyboard = true) {
        $keyboardBack = [
            "one_time" => true,
            "buttons" => (array)[
                (array)[
                    (object)[
                        "action" => (object)[
                            "type" => "text",
                            "label" => "Назад",
                            "payload" => null
                        ],
                        "color" => "positive"
                    ]
                ]
            ]
        ];
        $keyboardMain = [
            "one_time" => true,
            "buttons" => (array)[
                (array)[
                    (object)[
                        "action" => (object)[
                            "type" => "text",
                            "label" => "Мои товары",
                            "payload" => null
                        ],
                        "color" => "positive"
                    ]
                ],
                (array)[
                    (object)[
                        "action" => (object)[
                            "type" => "text",
                            "label" => "Добавить товар",
                            "payload" => null
                        ],
                        "color" => "positive"
                    ],
                    (object)[
                        "action" => (object)[
                            "type" => "text",
                            "label" => "Удалить товар",
                            "payload" => null
                        ],
                        "color" => "positive"
                    ]
                ],
                (array)[
                    (object)[
                        "action" => (object)[
                            "type" => "text",
                            "label" => "Список магазинов",
                            "payload" => null
                        ],
                        "color" => "positive"
                    ],
                    (object)[
                        "action" => (object)[
                            "type" => "text",
                            "label" => "Помощь",
                            "payload" => null
                        ],
                        "color" => "positive"
                    ]
                ]
            ]
        ];

        $params = [
            'message' => $message,
            'peer_id' => $user_id,
            'random_id' => '0'
        ];

        if ($main_keyboard) {
            $params['keyboard'] = json_encode((object)$keyboardMain);
        }else {
            $params['keyboard'] = json_encode((object)$keyboardBack);
        }


        $this->apiCall('messages.send', $params);
    }


    public function getUserName($user_id) {
        $params = [
            'user_ids' => $user_id
        ];
        return $this->apiCall('users.get', $params)['response'][0]['first_name'];
    }

    public function getMessageHistory($count, $user_id) {
        $params = [
            'offset' => 0,
            'count' => $count,
            'user_id' => $user_id,
            'group_id' => 183801846
        ];
        return $this->apiCall('messages.getHistory', $params)['response']['items'];
    }
}