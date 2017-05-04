<?php

namespace App\Providers;

use App\Http\BladeDirectives\BladeDirective;
use App\Http\ViewComposers\LayoutComposer;
use App\Validators\CustomValidationRole;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use CustomValidationRole,BladeDirective;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->customValidator();

        $this->initBladeDirective();

        view()->composer('layouts.backend', LayoutComposer::class);


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);

        }
    }
}
