<?php
namespace App\Exceptions\Settings;
use Exception;
use Throwable;
class LastCategoryDelete extends Exception
{
    public function __construct(string $message = '', int $code = 100000841, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
