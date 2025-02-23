<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Services\ApiResponseService;

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
        Response::macro('success', function ($data = null, $message = 'Success', $status = 200) {
            return ApiResponseService::success($data, $message, $status);
        });

        Response::macro('error', function ($message = 'Error', $status = 400, $errors = []) {
            return ApiResponseService::error($message, $status, $errors);
        });

        Response::macro('unauthenticated', function ($message = 'Unauthorized') {
            return ApiResponseService::unauthenticated($message);
        });
    }
}
