<?php

return [
    'enabled' => env('AUDITING_ENABLED', true),
    'implementation' => OwenIt\Auditing\Models\Audit::class,
    'user' => [
        'morph_prefix' => 'user',
        'guards' => [
            'web',
            'api',
        ],
        'resolver' => OwenIt\Auditing\Resolvers\UserResolver::class,
    ],
    'resolvers' => [
        'ip_address' => OwenIt\Auditing\Resolvers\IpAddressResolver::class,
        'user_agent' => OwenIt\Auditing\Resolvers\UserAgentResolver::class,
        'url' => OwenIt\Auditing\Resolvers\UrlResolver::class,
    ],
    'events' => [
        'created',
        'updated',
        'deleted',
        'restored',
    ],
    'strict' => false,
    'audit_console' => true,
    'queue' => [
        'enable' => true,
        'connection' => env('QUEUE_CONNECTION', 'sync'),
        'queue' => 'default',
        'delay' => 0,
    ],
    'console' => [
        'enabled' => env('AUDIT_CONSOLE_ENABLED', true),
    ],
];