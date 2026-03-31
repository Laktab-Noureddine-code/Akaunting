<?php
namespace App\Events\Banking;
use Illuminate\Queue\SerializesModels;
class TransferPrinting
{
    use SerializesModels;
    public $transfer;
    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }
}
