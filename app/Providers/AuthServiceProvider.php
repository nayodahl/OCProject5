<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define(
            'see-backoffice', function ($user) {
                return $user->isAdmin
                    ? Response::allow()
                    : Response::deny('You must be an administrator.');                
            }
        );

    
        Gate::define(
            'update-post', function ($user, $post) {
                return $user->id === $post->user_id;
            }
        );
    }
}
