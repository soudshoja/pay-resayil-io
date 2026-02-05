<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use App\Services\ResayilWhatsAppService;
use App\Services\MyFatoorahService;
use App\Services\OTPService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register WhatsApp Service
        $this->app->singleton(ResayilWhatsAppService::class, function ($app) {
            return new ResayilWhatsAppService();
        });

        // Register OTP Service
        $this->app->singleton(OTPService::class, function ($app) {
            return new OTPService(
                $app->make(ResayilWhatsAppService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Custom Blade directives
        Blade::directive('money', function ($expression) {
            return "<?php echo number_format($expression, 3) . ' KWD'; ?>";
        });

        Blade::directive('rtl', function () {
            return "<?php echo app()->getLocale() === 'ar' ? 'rtl' : 'ltr'; ?>";
        });

        Blade::directive('dir', function () {
            return "<?php echo app()->getLocale() === 'ar' ? 'dir=\"rtl\"' : 'dir=\"ltr\"'; ?>";
        });

        // Share common data with all views
        view()->composer('*', function ($view) {
            $view->with('currentLocale', app()->getLocale());
            $view->with('isRtl', app()->getLocale() === 'ar');
        });
    }
}
