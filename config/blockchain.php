<?php

return [
    'provider' => 'http://127.0.0.1:8555', // Your Ganache URL
    'network_id' => '5777', // Ganache default network ID
    'gas_price' => '20000000000', // 20 Gwei
    'gas_limit' => '6721975', // Ganache default block gas limit
    'confirmations' => 2,
    'accounts' => [
        'from' => '0xd38d3129d4359f93fcfe5323c40af90491da7b32', // Your first Ganache account
    ],
];