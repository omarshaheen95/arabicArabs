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
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';


    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
     protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->mapManagerRoutes();
        $this->mapSchoolRoutes();
        $this->mapSupervisorRoutes();
        $this->mapTeacherRoutes();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware(['web','local'])
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function mapManagerRoutes()
    {
        Route::group([
            'middleware' => ['web', 'manager', 'auth:manager','checkIfActive','local'],
            'prefix' => 'manager',
            'as' => 'manager.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/manager.php');
        });
    }

    protected function mapSchoolRoutes()
    {
        Route::group([
            'middleware' => ['web', 'school', 'auth:school','checkIfActive','local'],
            'prefix' => 'school',
            'as' => 'school.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/school.php');
        });
    }

    protected function mapSupervisorRoutes()
    {
        Route::group([
            'middleware' => ['web', 'supervisor', 'auth:supervisor','checkIfActive','local'],
            'prefix' => 'supervisor',
            'as' => 'supervisor.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/supervisor.php');
        });
    }

    protected function mapTeacherRoutes()
    {
        Route::group([
            'middleware' => ['web', 'teacher', 'auth:teacher','checkIfActive','local'],
            'prefix' => 'teacher',
            'as' => 'teacher.',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/teacher.php');
        });
    }
}
