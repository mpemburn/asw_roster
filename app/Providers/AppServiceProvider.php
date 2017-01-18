<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Membership;

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
            return Membership::isValidEmail($value);
        });
        /* Used for registration form.  Password mask validation  */
        Validator::extend('bad_pattern', function ($attribute, $value, $parameters) {
            $has_spaces = (preg_match('/\s/',$value) > 0);
            $has_lowercase = ($value != strtoupper($value));
            $has_uppercase = ($value != strtolower($value));
            $has_nonalpha = (!ctype_alpha($value));
            return (!$has_spaces && $has_lowercase && $has_uppercase && $has_nonalpha);
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
