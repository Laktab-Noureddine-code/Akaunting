<?php
namespace App\Http\Controllers\Install;
use App\Http\Requests\Install\Database as Request;
use App\Utilities\Installer;
use Illuminate\Routing\Controller;
class Database extends Controller
{
    public function create()
    {
        return view('install.database.create', [
            'host'      => env('DB_HOST'    , 'localhost'),
            'username'  => env('DB_USERNAME', ''),
            'password'  => env('DB_PASSWORD', ''),
            'database'  => env('DB_DATABASE', ''),
        ]);
    }
    public function store(Request $request)
    {
        $connection = config('database.default','mysql');
        $host     = $request['hostname'];
        $port     = config("database.connections.$connection.port", '3306');
        $database = $request['database'];
        $username = $request['username'];
        $password = $request['password'];
        $prefix   = config("database.connections.$connection.prefix", null);
        if (!Installer::createDbTables($host, $port, $database, $username, $password, $prefix)) {
            $response = [
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('install.error.connection'),
                'data' => null,
                'redirect' => null,
            ];
        }
        if (empty($response)) {
            $response['redirect'] = route('install.settings');
        }
        return response()->json($response);
    }
}
