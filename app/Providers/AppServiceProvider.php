<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\UserTemplate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         View::composer('layouts.dashboard', function ($view) {

            if (auth()->check()) {
                $latestTemplate = UserTemplate::where('user_id', auth()->id())
                    ->orderBy('updated_at', 'desc')
                    ->first();

                $view->with('latestTemplate', $latestTemplate);
            }
        });
    }
}
