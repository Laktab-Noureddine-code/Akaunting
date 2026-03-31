<?php
namespace App\Providers;
use App\Interfaces\Utility\DocumentNumber as DocumentNumberInterface;
use App\Interfaces\Utility\TransactionNumber as TransactionNumberInterface;
use App\Utilities\DocumentNumber;
use App\Utilities\TransactionNumber;
use Illuminate\Support\ServiceProvider;
class Binding extends ServiceProvider
{
    public array $bindings = [
        DocumentNumberInterface::class => DocumentNumber::class,
        TransactionNumberInterface::class => TransactionNumber::class,
    ];
}
