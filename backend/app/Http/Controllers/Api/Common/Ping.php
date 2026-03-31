<?php
namespace App\Http\Controllers\Api\Common;
use App\Abstracts\Http\ApiController;
use App\Utilities\Date;
class Ping extends ApiController
{
    public function __construct()
    {
    }
    public function pong()
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => Date::now(),
        ]);
    }
}
