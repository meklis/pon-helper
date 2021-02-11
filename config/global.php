<?php

use Monolog\Logger;

return [
    'api' => [
       'auth' => [
           'key_expired_sec' =>  86400,
           'rules' => yaml_parse_file(__DIR__ . '/rules.yml'),
           'strict_rules' => true,
       ],
    ],
    'console' => yaml_parse_file(__DIR__ . '/console.yml'),
    'devices' => [
        'model_params' => [
            'telnet_port' => ['value' => 23, 'name' => 'Telnet port'],
            'telnet_timeout' => ['value' => 10, 'name' => 'Telnet timeout sec'],
            'snmp_timeout' => ['value' => 2, 'name' => 'Snmp timeout sec'],
            'snmp_repeats' => ['value' => 2, 'name' => 'Snmp repeats'],
            'mikrotik_api_port' => ['value' => 8976, 'name' => 'API port(only for RouterOS)'],
        ],
        'device_params' => [

        ],
    ],
    'production' => _env('ENVIRONMENT') == 'PROD',
    'logger' => [
        'name' => 'system',
        'path' => _env('LOG_DIR') . "/system.log",
        'level' => Logger::toMonologLevel(_env('LOG_LEVEL')),
    ],
];