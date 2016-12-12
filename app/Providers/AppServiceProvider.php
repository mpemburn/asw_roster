<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use App\Models\TblMember;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* Used for registration form.  Must already be a member with an email address that matches $value */
        Validator::extend('member_email', function ($attribute, $value, $parameters) {
            $is_accepted = TblMember::is_valid_email($value);
            return $is_accepted;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Iber\Generator\ModelGeneratorProvider');
        }
    }
}
