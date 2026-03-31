<?php
return [
    'enabled' => env('FIREWALL_ENABLED', false),
    'whitelist' => explode(',', env('FIREWALL_WHITELIST', '')),
    'models' => [
        'user' => '\App\Models\Auth\User',
    ],
    'log' => [
        'max_request_size' => 2048,
    ],
    'cron' => [
        'enabled' => env('FIREWALL_CRON_ENABLED', true),
        'expression' => env('FIREWALL_CRON_EXPRESSION', '* * * * *'),
    ],
    'responses' => [
        'block' => [
            'view' => env('FIREWALL_BLOCK_VIEW', null),
            'redirect' => env('FIREWALL_BLOCK_REDIRECT', null),
            'abort' => env('FIREWALL_BLOCK_ABORT', false),
            'code' => env('FIREWALL_BLOCK_CODE', 403),
        ],
    ],
    'notifications' => [
        'mail' => [
            'enabled' => env('FIREWALL_EMAIL_ENABLED', false),
            'name' => env('FIREWALL_EMAIL_NAME', 'Akaunting Firewall'),
            'from' => env('FIREWALL_EMAIL_FROM', 'firewall@mydomain.com'),
            'to' => env('FIREWALL_EMAIL_TO', 'admin@mydomain.com'),
            'queue' => env('FIREWALL_EMAIL_QUEUE', 'default'),
        ],
        'slack' => [
            'enabled' => env('FIREWALL_SLACK_ENABLED', false),
            'emoji' => env('FIREWALL_SLACK_EMOJI', ':fire:'),
            'from' => env('FIREWALL_SLACK_FROM', 'Akaunting Firewall'),
            'to' => env('FIREWALL_SLACK_TO'), 
            'channel' => env('FIREWALL_SLACK_CHANNEL', null), 
            'queue' => env('FIREWALL_SLACK_QUEUE', 'default'),
        ],
    ],
    'all_middleware' => [
        'firewall.ip',
        'firewall.agent',
        'firewall.bot',
        'firewall.geo',
        'firewall.lfi',
        'firewall.php',
        'firewall.referrer',
        'firewall.rfi',
        'firewall.session',
        'firewall.swear',
        'firewall.xss',
    ],
    'middleware' => [
        'ip' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_IP_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
        ],
        'agent' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_AGENT_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'browsers' => [
                'allow' => [], 
                'block' => [], 
            ],
            'platforms' => [
                'allow' => [], 
                'block' => [], 
            ],
            'devices' => [
                'allow' => [], 
                'block' => [], 
            ],
            'properties' => [
                'allow' => [], 
                'block' => [], 
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_AGENT_AUTO_BLOCK_ATTEMPTS', 5),
                'frequency' => 1 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'bot' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_BOT_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'crawlers' => [
                'allow' => [], 
                'block' => [], 
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_BOT_AUTO_BLOCK_ATTEMPTS', 5),
                'frequency' => 1 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'geo' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_GEO_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'continents' => [
                'allow' => [], 
                'block' => [], 
            ],
            'regions' => [
                'allow' => [], 
                'block' => [], 
            ],
            'countries' => [
                'allow' => [], 
                'block' => [], 
            ],
            'cities' => [
                'allow' => [], 
                'block' => [], 
            ],
            'service' => 'ipapi',
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_GEO_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'lfi' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_LFI_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['get', 'delete'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [], 
            ],
            'patterns' => [
                '
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_LFI_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'login' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_LOGIN_ENABLED', env('FIREWALL_ENABLED', true)),
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_LOGIN_AUTO_BLOCK_ATTEMPTS', 10),
                'frequency' => 1 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'php' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_PHP_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['get', 'post', 'delete'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [], 
            ],
            'patterns' => [
                'bzip2://',
                'expect://',
                'glob://',
                'phar://',
                'php://',
                'ogg://',
                'rar://',
                'ssh2://',
                'zip://',
                'zlib://',
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_PHP_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'referrer' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_REFERRER_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'blocked' => [],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_REFERRER_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'rfi' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_RFI_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['get', 'post', 'delete'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [
                    'body', 
                ], 
            ],
            'patterns' => [
                '
            ],
            'exceptions' => [],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_RFI_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'session' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SESSION_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['get', 'post', 'delete'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [], 
            ],
            'patterns' => [
                '@[\|:]O:\d{1,}:"[\w_][\w\d_]{0,}":\d{1,}:{@i',
                '@[\|:]a:\d{1,}:{@i',
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_SESSION_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'sqli' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SQLI_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['get', 'delete'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [], 
            ],
            'patterns' => [
                '
                '
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_SQLI_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'swear' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['post', 'put', 'patch'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [], 
            ],
            'words' => [],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_SWEAR_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'url' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_URL_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'inspections' => [], 
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_URL_AUTO_BLOCK_ATTEMPTS', 5),
                'frequency' => 1 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'whitelist' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_WHITELIST_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['all'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
        ],
        'xss' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_XSS_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['post', 'put', 'patch'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'inputs' => [
                'only' => [], 
                'except' => [], 
            ],
            'patterns' => [
                '
                '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
                '
                '
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_XSS_AUTO_BLOCK_ATTEMPTS', 3),
                'frequency' => 5 * 60, 
                'period' => 30 * 60, 
            ],
        ],
        'too_many_emails_sent' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_TOO_MANY_EMAILS_SENT_ENABLED', env('FIREWALL_ENABLED', true)),
            'methods' => ['post'],
            'routes' => [
                'only' => [], 
                'except' => [], 
            ],
            'auto_block' => [
                'attempts' => env('FIREWALL_MIDDLEWARE_TOO_MANY_EMAILS_SENT_AUTO_BLOCK_ATTEMPTS', 20),
                'frequency' => 1 * 60, 
                'period' => 30 * 60, 
            ],
        ],
    ],
];
