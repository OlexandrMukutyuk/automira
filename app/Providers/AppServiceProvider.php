<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
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
        Http::macro('automira', function () {
            return Http::withBasicAuth(
                config('services.automira.login'),
                config('services.automira.pass')
            )->baseUrl(config('services.automira.domain'));
        });


        Collection::macro('orderFilter', function (string $filterKey, array $filterValues) {
            return $this
                ->when(!!count($filterValues))
                ->filter(function ($element) use ($filterKey, $filterValues) {
                    return in_array($element[$filterKey], $filterValues);
                });
        });
    }
}
