<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::directive('age',function($expression) {
            $data = json_decode($expression);
            $year = $data[0];
            $month = $data[1];
            $day = $data[2];
            $age = Carbon::createFromDate($year,$month,$day)->age;
            return "<?php echo $age; ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $age = Carbon::createFromDate(1994,11,10)->age;
        view()->share('myname', 'DustyDrey');  
        view()->share('age', $age);

        view()->composer('*', function($view) {
            $view->with('auth', Auth::user());
        });
    }
}
