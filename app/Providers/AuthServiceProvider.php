<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('acesso-funcionario', function(User $user){
            return $user->role == "funcionario"
                ? Response::allow()
                : Response::deny();
        });

        Gate::define('acesso-cliente', function(User $user){
            return $user->role == "cliente"
                ? Response::allow()
                : Response::deny();
        });
    }
}
