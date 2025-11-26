<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 🏠 المسار الافتراضي بعد تسجيل الدخول
     * يُستخدم في إعادة التوجيه الذكي حسب نوع المستخدم.
     */
    public const HOME = '/redirect-after-login';

    /**
     * 🔧 تسجيل جميع المسارات في التطبيق.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            /*
            |--------------------------------------------------------------------------
            | 🌐 API Routes — واجهة برمجة التطبيقات
            |--------------------------------------------------------------------------
            */
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            /*
            |--------------------------------------------------------------------------
            | 👤 Web Routes — الواجهة العامة ولوحة المستخدم
            |--------------------------------------------------------------------------
            */
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            /*
            |--------------------------------------------------------------------------
            | 🎮 Games Routes — مسارات الألعاب التعليمية
            |--------------------------------------------------------------------------
            */
            Route::middleware('web')
                ->group(base_path('routes/games.php'));

            /*
            |--------------------------------------------------------------------------
            | 👑 Admin Routes — لوحة الإدارة
            |--------------------------------------------------------------------------
            */
            Route::middleware(['web', 'auth', 'role:admin'])
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * ⚙️ تهيئة تحديد معدل استخدام الـ API (Rate Limiting)
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
