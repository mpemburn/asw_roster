<?php
namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Services\LeadershipRoleService;
use App\Services\MemberService;
use App\Services\RosterAuthService;
use App\Services\RbacService;

/**
 * Register our Repository with Laravel
 */
class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Registers Interfaces and Classes with Laravel's IoC Container
     *
     */
    public function register()
    {
        App::bind('LeadershipRoleService', function()
        {
            return new LeadershipRoleService();
        });

        App::bind('MemberService', function()
        {
            return new MemberService();
        });

        App::bind('RosterAuthService', function()
        {
            return new RosterAuthService();
        });

        App::bind('RbacService', function()
        {
            return new RbacService();
        });

    }
}