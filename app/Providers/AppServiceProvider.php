<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        Validator::extend(
            'exists_or_null',
            function ($attribute, $value, $parameters) {
                if ($value == 0 || is_null($value)) {
                    return true;
                } else {
                    $validator = Validator::make([$attribute => $value], [
                        $attribute => 'exists:' . implode(",", $parameters),
                    ]);
                    return !$validator->fails();
                }
            }
        );
    }
}
