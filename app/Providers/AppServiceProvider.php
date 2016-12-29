<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use App\Facades\Member;

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
            return Member::isValidEmail($value);
        });
        /* Used for registration form.  Password mask validation  */
        Validator::extend('bad_pattern', function ($attribute, $value, $parameters) {
            $has_spaces = (!preg_match('/\s/',$value));
            $has_lowercase = ($value != strtoupper($value));
            $has_uppercase = ($value != strtolower($value));
            $has_nonalpha = (!ctype_alpha($value));
            return ($has_spaces && $has_lowercase && $has_uppercase && $has_nonalpha);
        });
    }
//theN1ceguy
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
