<?php
return [
    'enabled'       => env('READ_ONLY_ENABLED', false),
    'allow_login'   => env('READ_ONLY_LOGIN', true),
    'login_route'   => 'login.store',
    'logout_route'  => 'logout',
    'methods'       => explode(',', env('READ_ONLY_METHODS', 'post,put,patch,delete')),
    'whitelist' => [
    ],
    'livewire'      => explode(',', env('READ_ONLY_LIVEWIRE', 'menu.notifications,menu.settings,menu.neww')),
];
