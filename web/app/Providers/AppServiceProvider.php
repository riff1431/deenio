<?php

namespace App\Providers;

use App\Config\SetEmailConfig;
use App\Services\Setting\SettingService;
use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (str_starts_with((string) config('app.url'), 'https://') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        try {
            $settings = resolve(SettingService::class)->getFormattedSettings('app');
            View::composer('*', function ($view) use ($settings) {
                $view->with('settings', $settings);
            });

            foreach ($settings as $key => $setting) {
                if ($key == 'company_name') {
                    config()->set('app.name', $setting);
                }
                config()->set('settings.application.' . $key, $setting);
            }
        } catch (Exception $exception) {
        }


        try {
            SetEmailConfig::new(true)
                ->clear()
                ->set();
        } catch (Exception $exception) {
        }

    }
}
