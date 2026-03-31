<?php
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;
return [
    'banking' => [
        'transactions' => [
            'document_link' => 'https://akaunting.com/hc/docs/import-export/importing-transactions/',
            'view' => 'banking.transactions.import',
        ],
    ],
];
