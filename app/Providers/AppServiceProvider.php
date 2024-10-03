<?php




namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Paginator::useBootstrap();
        // ************************ Macro Defincation ***********************//
        // Number format
        Str::macro('currency', function ($price) {
            return number_format($price, 2, '.', '');
        });


        Str::macro('documentPadding', function ($number) {
            return Str::padLeft($number, 4, '0');
        });

        Blade::directive('removeComments', function ($expression) {
            return "<?php echo preg_replace('/<!--(.|\s)*?-->/', '', $expression); ?>";
        });
        // ************************ End of Macro Defincation ***********************//

        //        if(!Auth::check())
        //        {
        //
        //           // Auth::logout();
        //            return view('authentication.login');
        //            die();
        //        }


        /////////////////////////  Config value set //////////////////////
        // Following is how to access and reset config value, comment code is for ref.
        //config(['app_session.user_id'=>'Dada g']);      // T
        // $mail = config('app_session.user_id');
        ///////////////////////// End of Config value set //////////////////////

        ///////////////////////////////////////////////////////////////////////////
        // This section generate left side bar module content.
        // Currrently it is displaying data from sys_module,
        // we need to set rigthts then it will deisplay data from user content table.
        //

        // $user = Auth::user();
        //dd($user);
        // dd(Auth::user()->role);

        view()->composer('layout.sidebar', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $company = DB::table('user_companies')
                    ->leftJoin('sys_companies as companies', 'companies.id', '=', 'user_companies.company_id')
                    ->leftJoin('sys_currencies', 'sys_currencies.code', '=', 'companies.currency')
                    ->select('company_id', 'sys_currencies.code', 'companies.currency', 'sys_currencies.symbol', 'companies.multi_currency')
                    ->where('user_id', Auth::id())->first();

                $sw = array(
                    'companyId' => $company->company_id,
                    'code' => $company->currency,
                    'symbol' => $company->symbol,
                    'multi_currency' => $company->multi_currency,
                    'userId' => Auth::id(),
                    'voucherApproval_first' => true,
                    'voucherApproval_second' => false,
                    'dateFormat' => 'mm/dd/YYYY',
                    'month' => dbLib::getMonth(date("y-m-d")),
                );

                session($sw);

                // $modules = $this->getmodules($user->role);
                // $view->with('modules', $modules);

                $currenturl = Request::path();

                $vali_url = DB::table('sys_modules')
                    ->join('sys_roles', 'sys_modules.id', '=', 'sys_roles.mdl_mdc_id')
                    ->select('sys_modules.*', 'sys_roles.name', 'sys_roles.mdl_id')
                    ->where('sys_modules.route', '=', $currenturl)
                    ->where('sys_roles.name', '=', $user->role)
                    ->first();

               // if ($vali_url || ('monmatics/authentication/login'==$currenturl ))
                if(1)
                {
                // echo "record found";
                    $modules = $this->getmodules($user->role);
                    $view->with('modules', $modules);

                } else {
                    // echo "not found";
                    $modules = $this->getmodules($user->role);
                    $view->with('modules', $modules);
                    header('Location:' . url('error'));
                    exit;
                }

            } else {
                header('Location:' . url('authentication/login'));
                exit;
            }
        });
    }

    public function getmodules($role)
    {
        $modules_result = DB::table('sys_modules')
            ->distinct('sys_modules.id')
            ->join('sys_roles', 'sys_roles.mdl_id', '=', 'sys_modules.id')
            ->select('sys_modules.id', 'sys_modules.status', 'display_order')
            ->where([
                ['sys_roles.name', $role],
                ['sys_modules.status', '=', 1],
            ])
            ->orderBy('display_order')
            ->get();

        $arrmodules = array();
        foreach ($modules_result as $module) {
            $mainModule = DB::table('sys_modules')->where('id', '=', $module->id)->first();
            if ($mainModule->has_child == 1)
                $mainModule->has_child = $this->getChildModules($mainModule->id, $role);

            $arrmodules[] = $mainModule;
        }

        return $arrmodules;
    }

    private function getChildModules($module_id, $role)
    {
        $modules_result = DB::table('sys_modules')
            ->distinct('sys_modules.id')
            ->select()
            ->join('sys_roles', 'sys_roles.mdl_mdc_id', '=', 'sys_modules.id')
            ->where([
                ['sys_modules.status', '=', 1],
                ['sys_roles.name', '=', $role],
                ['sys_modules.mdl_id', '=', $module_id],
                ['sys_roles.mdl_id', '=', $module_id],
            ])
            ->orderBy('display_order')
            ->get();
        $modules = $modules_result->toArray();
        $arrmodules = array();
        foreach ($modules as $module) {
            $arrmodules[] = $module;
        }
        return  $arrmodules;
    }
}
