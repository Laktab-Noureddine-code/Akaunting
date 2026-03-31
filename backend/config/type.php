<?php
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;
return [
    'category' => [
        Category::INCOME_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
        Category::EXPENSE_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
        Category::ITEM_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
        Category::OTHER_TYPE => [
            'alias'             => '',
            'translation' => [
                'prefix'        => 'general',
            ],
        ],
    ],
    'contact' => [
        Contact::CUSTOMER_TYPE => [
            'alias'                 => '', 
            'group'                 => 'sales',
            'route' => [
                'prefix'            => 'customers', 
                'parameter'         => 'customer', 
            ],
            'permission' => [
                'prefix'            => 'customers',
            ],
            'translation' => [
                'prefix'                        => 'customers', 
                'section_general_description'   => 'customers.form_description.general',
                'section_billing_description'   => 'customers.form_description.billing',
                'section_address_description'   => 'customers.form_description.address',
            ],
            'category_type'         => 'income',
            'document_type'         => 'invoice',
            'transaction_type'      => 'income',
            'hide'                  => [],
            'class'                 => [],
            'script' => [
                'folder'            => 'common',
                'file'              => 'contacts',
            ],
        ],
        Contact::VENDOR_TYPE => [
            'alias'                 => '', 
            'group'                 => 'purchases',
            'route' => [
                'prefix'            => 'vendors', 
                'parameter'         => 'vendor', 
            ],
            'permission' => [
                'prefix'            => 'vendors',
            ],
            'translation' => [
                'prefix'                        => 'vendors', 
                'section_general_description'   => 'vendors.form_description.general',
                'section_billing_description'   => 'vendors.form_description.billing',
                'section_address_description'   => 'vendors.form_description.address',
            ],
            'category_type'         => 'expense',
            'document_type'         => 'bill',
            'transaction_type'      => 'expense',
            'hide'                  => [],
            'class'                 => [],
            'script' => [
                'folder'            => 'common',
                'file'              => 'contacts',
            ],
        ],
    ],
    'document' => [
        Document::INVOICE_TYPE => [
            'alias'                     => '', 
            'group'                     => 'sales', 
            'route' => [
                'prefix'                => 'invoices', 
                'parameter'             => 'invoice', 
                'document'              => 'invoices.index',
                'recurring'             => 'recurring-invoices.index',
                'params' => [
                    'unpaid'            => ['search' => 'status:sent,viewed,partial'],
                    'draft'             => ['search' => 'status:draft'],
                    'all'               => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'                => 'invoices', 
            ],
            'translation' => [
                'prefix'                        => 'invoices', 
                'add_contact'                   => 'general.customers', 
                'issued_at'                     => 'invoices.invoice_date',
                'due_at'                        => 'invoices.due_date',
                'section_billing_description'   => 'invoices.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'invoice',
            ],
            'category_type'             => 'income',
            'transaction_type'          => 'income',
            'contact_type'              => 'customer', 
            'inventory_stock_action'    => 'decrease', 
            'transaction' => [
                'email_template'        => 'invoice_payment_customer', 
            ],
            'hide'                      => [], 
            'class'                     => [],
            'notification' => [
                'class'                 => 'App\Notifications\Sale\Invoice',
                'notify_contact'        => true,
                'notify_user'           => true,
            ],
            'auto_send' => 'App\Events\Document\DocumentSent',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'send',
                'sent'                  => 'get-paid',
                'viewed'                => 'get-paid',
                'partial'               => 'get-paid',
                'paid'                  => 'get-paid',
                'cancelled'             => 'restore',
            ],
        ],
        Document::INVOICE_RECURRING_TYPE => [
            'alias'                     => '', 
            'group'                     => 'sales', 
            'route' => [
                'prefix'                => 'recurring-invoices', 
                'parameter'             => 'recurring_invoice', 
                'document'              => 'invoices.index',
                'recurring'             => 'recurring-invoices.index',
                'end'                   => 'recurring-invoices.end',
            ],
            'permission' => [
                'prefix'                => 'invoices', 
            ],
            'translation' => [
                'prefix'                        => 'invoices', 
                'add_contact'                   => 'general.customers', 
                'issued_at'                     => 'invoices.invoice_date',
                'due_at'                        => 'invoices.due_date',
                'tab_document'                  => 'general.invoices',
                'section_billing_description'   => 'invoices.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'invoice',
            ],
            'category_type'             => 'income',
            'transaction_type'          => 'income',
            'contact_type'              => 'customer', 
            'inventory_stock_action'    => 'decrease', 
            'hide'                      => [], 
            'class'                     => [],
            'notification' => [
            ],
            'auto_send'                 => 'App\Events\Document\DocumentSent',
            'image_empty_page'          => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'schedule',
                'active'                => 'schedule',
                'end'                   => 'schedule',
            ],
        ],
        Document::BILL_TYPE => [
            'alias'                     => '',
            'group'                     => 'purchases',
            'route' => [
                'prefix'                => 'bills',
                'parameter'             => 'bill',
                'document'              => 'bills.index',
                'recurring'             => 'recurring-bills.index',
                'params' => [
                    'unpaid'            => ['search' => 'status:received,partial'],
                    'draft'             => ['search' => 'status:draft'],
                    'all'               => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'                => 'bills',
            ],
            'translation' => [
                'prefix'                        => 'bills',
                'issued_at'                     => 'bills.bill_date',
                'due_at'                        => 'bills.due_date',
                'section_billing_description'   => 'bills.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'bill',
            ],
            'category_type'             => 'expense',
            'transaction_type'          => 'expense',
            'contact_type'              => 'vendor',
            'inventory_stock_action'    => 'increase', 
            'transaction' => [
                'email_template'        => 'invoice_payment_customer', 
            ],
            'hide'                      => [],
            'notification' => [
                'class'                 => 'App\Notifications\Purchase\Bill',
                'notify_contact'        => false,
                'notify_user'           => true,
            ],
            'auto_send' => 'App\Events\Document\DocumentReceived',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'receive',
                'received'              => 'make-payment',
                'viewed'                => 'make-payment',
                'partial'               => 'make-payment',
                'paid'                  => 'make-payment',
                'cancelled'             => 'restore',
            ],
        ],
        Document::BILL_RECURRING_TYPE => [
            'alias'                     => '',
            'group'                     => 'purchases',
            'route' => [
                'prefix'                => 'recurring-bills',
                'parameter'             => 'recurring_bill',
                'document'              => 'bills.index',
                'recurring'             => 'recurring-bills.index',
                'end'                   => 'recurring-bills.end',
            ],
            'permission' => [
                'prefix'                => 'bills',
            ],
            'translation' => [
                'prefix'                        => 'bills',
                'issued_at'                     => 'bills.bill_date',
                'due_at'                        => 'bills.due_date',
                'tab_document'                  => 'general.bills',
                'section_billing_description'   => 'bills.form_description.billing',
            ],
            'setting' => [
                'prefix'                => 'bill',
            ],
            'category_type'             => 'expense',
            'transaction_type'          => 'expense',
            'contact_type'              => 'vendor',
            'inventory_stock_action'    => 'increase', 
            'hide'                      => [],
            'class'                     => [],
            'notification' => [
            ],
            'auto_send'                 => 'App\Events\Document\DocumentReceived',
            'image_empty_page'          => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'                => 'common',
                'file'                  => 'documents',
            ],
            'status_workflow' => [
                'draft'                 => 'schedule',
                'active'                => 'schedule',
                'end'                   => 'schedule',
            ],
        ],
    ],
    'transaction' => [
        'transactions' => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'split_type'            => Transaction::INCOME_SPLIT_TYPE,
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::INCOME_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'split_type'            => Transaction::INCOME_SPLIT_TYPE,
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::INCOME_TRANSFER_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'split_type'            => Transaction::INCOME_SPLIT_TYPE,
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::INCOME_SPLIT_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'invoices.invoice_amount',
                'transactions'              => 'general.incomes',
            ],
            'contact_type'          => 'customer',
            'document_type'         => 'invoice',
            'email_template'        => 'payment_received_customer',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::INCOME_RECURRING_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'recurring-transactions', 
                'parameter'         => 'recurring_transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'            => 'transactions', 
                'new'               => 'general.recurring_incomes',
                'transactions'      => 'general.incomes',
            ],
            'image_empty_page'      => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
            'status_workflow' => [
                'draft'             => 'schedule',
                'active'            => 'schedule',
                'end'               => 'schedule',
            ],
        ],
        Transaction::EXPENSE_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'bills.bill_amount',
            ],
            'contact_type'          => 'vendor',
            'document_type'         => 'bill',
            'split_type'            => Transaction::EXPENSE_SPLIT_TYPE,
            'email_template'        => 'payment_made_vendor',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::EXPENSE_TRANSFER_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'bills.bill_amount',
            ],
            'contact_type'          => 'vendor',
            'document_type'         => 'bill',
            'split_type'            => Transaction::EXPENSE_SPLIT_TYPE,
            'email_template'        => 'payment_made_vendor',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::EXPENSE_SPLIT_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'transactions', 
                'parameter'         => 'transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'                    => 'transactions', 
                'related_document_amount'   => 'bills.bill_amount',
            ],
            'contact_type'          => 'vendor',
            'document_type'         => 'bill',
            'email_template'        => 'payment_made_vendor',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
        ],
        Transaction::EXPENSE_RECURRING_TYPE => [
            'group'                 => 'banking',
            'route' => [
                'prefix'            => 'recurring-transactions', 
                'parameter'         => 'recurring_transaction', 
                'params' => [
                    'income'        => ['search' => 'type:income'],
                    'expense'       => ['search' => 'type:expense'],
                    'all'           => ['list_records' => 'all'],
                ],
            ],
            'permission' => [
                'prefix'            => 'transactions',
            ],
            'translation' => [
                'prefix'            => 'transactions', 
                'new'               => 'general.recurring_expenses',
                'transactions'      => 'general.expenses',
            ],
            'image_empty_page'      => 'public/img/empty_pages/recurring_templates.png',
            'script' => [
                'folder'            => 'banking',
                'file'              => 'transactions',
            ],
            'status_workflow' => [
                'draft'             => 'schedule',
                'active'            => 'schedule',
                'end'               => 'schedule',
            ],
        ],
    ],
];
