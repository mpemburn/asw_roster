<?php namespace Repositories\Member;

use App\Models\Member;
use Repositories\Member\MemberRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Register our Repository with Laravel
 */
class MemberRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registers the memberInterface with Laravels IoC Container
     *
     */
    public function register()
    {
        // Bind the returned class to the namespace 'Repositories\MemberInterface
        $this->app->bind('Repositories\Member\MemberInterface', function($app)
        {
            return new MemberRepository(new Member());
        });
    }
}