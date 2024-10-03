<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AppRoutesAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        // session_start();
        $currentRoute = $request->path();

        //  dd($currentRoute);
        //  dd(session('db_username'));
        //  dd(session()->all());
        //  $connectionConfig = [
        //      'driver' => 'mysql',
        //      'host' => '127.0.0.1',
        //      'port' => '3306',
        //      'database' => session('db_name'),
        //      'username' => session('db_username'),
        //      'password' => session('db_password'),
        //  ];

        //  config(['database.connections.mysql' => $connectionConfig]);
        //  DB::purge('mysql');

        $Route = DB::table('sys_modules')
            ->where('route', $currentRoute)
            ->first();
        if ($Route) {
            $RouteCheck = DB::table('sys_roles')
                ->where('name', Auth::user()->role)
                ->where('mdl_mdc_id', $Route->id)
                ->first();
            // dd($RouteCheck);

            if (!$RouteCheck) {

                return redirect('Page/404');
            }
        }


        return $next($request);
    }













    //  public function handle($request, Closure $next)
    //  {
    //     //  $currentRoute = $request->route()->getName();
    //     $currentRoute = $request->path();
    //     //  dd($currentRoute);

    //     // dd(session()->all());
    //     $connectionConfig = [
    //         'driver' => 'mysql',
    //         'host' => '127.0.0.1',
    //         'port' => '3306',
    //         'database' => session('db_name'),
    //         'username' => session('db_username'),
    //         'password' => session('db_password'),
    //     ];

    //     config(['database.connections.'.$databaseName => $connectionConfig]);
    //     DB::purge($databaseName);

    //     $restrictedRoutes = DB::table('sys_modules')
    //     ->where('route', $currentRoute)
    //     ->where('status', 0)
    //     ->exists();
    //     //  dd($restrictedRoutes);

    //      if ($restrictedRoutes) {
    //          // Redirect or handle unauthorized access for status 0
    //          return redirect('/dashboard/index');
    //      }

    //      return $next($request);
    //  }




}
