<?php
return [
    'use_morph_map' => true,
    'checker' => 'default',
    'cache' => [
        'enabled' => true,
        'expiration_time' => 3600,
    ],
    'user_models' => [
        'users' => 'App\Models\Auth\User',
    ],
    'models' => [
        'role' => 'App\Models\Auth\Role',
        'permission' => 'App\Models\Auth\Permission',
        'team' => 'App\Models\Auth\Team',
    ],
    'tables' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'teams' => 'teams',
        'role_user' => 'user_roles',
        'permission_user' => 'user_permissions',
        'permission_role' => 'role_permissions',
    ],
    'foreign_keys' => [
        'user' => 'user_id',
        'role' => 'role_id',
        'permission' => 'permission_id',
        'team' => 'team_id',
    ],
    'middleware' => [
        'register' => false,
        'handling' => 'abort',
        'handlers' => [
            'abort' => [
                'code' => 403,
                'message' => 'User does not have any of the necessary access rights.'
            ],
            'redirect' => [
                'url' => 'auth/login',
                'message' => [
                    'key' => 'error',
                    'content' => ''
                ]
            ]
        ]
    ],
    'teams' => [
        'enabled' => false,
        'strict_check' => false,
    ],
    'magic_is_able_to_method_case' => 'kebab_case',
    'permissions_as_gates' => true,
    'panel' => [
        'register' => false,
        'path' => 'laratrust',
        'go_back_route' => '/',
        'middleware' => ['web'],
        'assign_permissions_to_user' => true,
        'roles_restrictions' => [
            'not_removable' => [],
            'not_editable' => [],
            'not_deletable' => [],
        ],
    ],
];
