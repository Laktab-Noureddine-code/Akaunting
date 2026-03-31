<?php
return [
    'types' => [
        'alpha' => [
            'fields'                => ['name', 'contact_name', 'customer_name', 'vendor_name', 'display_name', 'company_name', 'domain', 'email', 'description', 'code', 'type', 'status', 'vendor', 'account', 'category', 'type'],
            'icon'                  => 'arrow_drop',
        ],
        'amount' => [
            'fields'                => ['amount', 'price', 'sale_price', 'purchase_price', 'total_price', 'current_balance', 'total_price', 'opening_balance'],
            'icon'                  => 'arrow_drop',
        ],
        'numeric' => [
            'fields'                => ['created_at', 'updated_at', 'paid_at', 'issued_at', 'due_at', 'id', 'quantity', 'rate',  'number', 'document_number'],
            'icon'                  => 'arrow_drop',
        ],
    ],
    'icons' => [
        'enabled'                   => true,
        'wrapper'                   => '<span class="material-icons-outlined text-xl align-middle" style="line-height:0">{icon}</span>',
        'default'                   => 'arrow_drop',
        'sortable'                  => null,
        'clickable'                 => false,
        'prefix'                    => '&nbsp;',
        'suffix'                    => '',
        'asc_suffix'                => '_down',
        'desc_suffix'               => '_up',
    ],
    'anchor_class'                  => null,
    'active_anchor_class'           => null,
    'direction_anchor_class_prefix' => null,
    'relation_column_separator'     => '.',
    'formatting_function'           => 'ucfirst',
    'format_custom_titles'          => true,
    'inject_title_as'               => null,
    'allow_request_modification'    => true,
    'default_direction'             => 'asc',
    'default_direction_unsorted'    => 'asc',
    'default_first_column'          => false,
    'join_type'                     => 'leftJoin',
];
