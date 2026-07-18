<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Media;
use App\Models\QrCode;
use App\Policies\MediaPolicy;
use App\Policies\QrCodePolicy;
use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerViewComposers();
        $this->registerBladeDirectives();
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(QrCode::class, QrCodePolicy::class);
    }

    protected function registerViewComposers(): void
    {
        view()->composer('*', function ($view) {
            $view->with('appSettings', Setting::getGroup('general'));
        });
    }

    protected function registerBladeDirectives(): void
    {
        Blade::directive('jalali', function (string $expression) {
            return "<?php echo \\Morilog\\Jalali\\Jalalian::fromCarbon({$expression})->format('Y/m/d'); ?>";
        });

        Blade::directive('jalaliTime', function (string $expression) {
            return "<?php echo \\Morilog\\Jalali\\Jalalian::fromCarbon({$expression})->format('Y/m/d H:i'); ?>";
        });

        Blade::directive('jalaliFull', function (string $expression) {
            return "<?php echo \\Morilog\\Jalali\\Jalalian::fromCarbon({$expression})->format('Y/m/d H:i:s'); ?>";
        });
    }
}
