<?php

return [
    'audit' => [
        'send' => false,
        'failed' => false,
    ],
    'max_tries' => 1,
    'sms_sender' => substr(config('app.name'), 0, 11),
    'slack' => [
        'sender' => config('app.name'),
        'sender_icon' => '',
    ],
];