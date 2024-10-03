<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SetDatabaseConnection
{
    public function handle($request, Closure $next)
    {

        $email = null;
        $routeName = $request->route()->getName();

        if ($routeName === 'authentication.login') {
            session()->forget(['db_email', 'db_name', 'db_username', 'db_password', 'db_host', 'db_port']);
        } else {
            if ($request->email) {
                $email = $request->email;
            }

            if (Auth::check()) {
                $email = auth()->user()->email;
            }
            $databaseDetails = $this->setGlobalDatabaseConnection($email);

            if ($databaseDetails) {
                $this->setDatabaseConnection($databaseDetails);
            } else {
                return redirect()->route('authentication.login');
            }
        }
        return $next($request);
    }

    private function setGlobalDatabaseConnection($email = null)
    {
        if (Auth::check()) {
            $email = auth()->user()->email;
        }



        if (Session::has('db_email') && Session::has('db_name')) {
            $db_email = Session::get('db_email');
            $db_name = Session::get('db_name');
            $db_username = Session::get('db_username');
            $db_password = Session::get('db_password');
            // $db_host = Session::get('db_host');
            // $db_port = Session::get('db_port');
        } else {
            $connection = DB::connection('mysql');
            $data = $connection->table('users')
                ->where('email', $email)
                ->first();
            if (empty(optional($data)->db_name)) {
                return null;
            }
            $db_email = $email;
            $db_name = $data->db_name;
            $db_username = $data->db_username;
            $db_password = $data->db_password;
            // $db_host=$data->db_host;
            // $db_port=$data->db_port;
            Session::put([
                'db_email' => $db_email,
                'db_name' => $data->db_name,
                'db_username' => $data->db_username,
                'db_password' => $data->db_password,
                // 'db_host' => $data->db_host,
                // 'db_port' => $data->db_port,
            ]);
        }

        //    if(isset($request->db_name_api) && $request->db_name_api){
        //     $db_name=$request->db_name_api;
        //     $db_username=$request->db_name_api;
        //     $db_password=$request->db_password_api;
        //    }
        return [
            'db_name' => $db_name,
            'db_username' => $db_username,
            'db_password' => $db_password,
            // 'db_host' => $db_host,
            // 'db_port' => $db_port,

        ];
    }

    private function setDatabaseConnection($databaseDetails)
    {

        DB::purge('mysql1');
        config([
            'database.connections.mysql1.driver' => 'mysql',
            // 'database.connections.mysql1.host' => $databaseDetails['db_host'],
            // 'database.connections.mysql1.port' => $databaseDetails['db_port'],
            'database.connections.mysql1.database' => $databaseDetails['db_name'],
            'database.connections.mysql1.username' => $databaseDetails['db_username'],
            'database.connections.mysql1.password' => $databaseDetails['db_password'],

        ]);

        try {
            DB::reconnect('mysql1');
            $connectionStatus = DB::connection('mysql1')->getDatabaseName();
        } catch (\Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        Config::set('database.default', 'mysql1');


        // $currentDatabase = DB::getDatabaseName();






    }
}
