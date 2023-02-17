<?php

namespace App\Providers;

use App\Models\Usuario;
use Laravel\Passport\Passport;
use App\Models\PermissoesModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        //Passport::routes();
        Passport::routes(function ($router) {
            $router->forAuthorization();
            $router->forAccessTokens();
            $router->forTransientTokens();
            $router->forClients();
            $router->forPersonalAccessTokens();
        });

        Passport::tokensExpireIn(now()->addMinutes(30));
        Passport::refreshTokensExpireIn(now()->addMinutes(60));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        //Passport::cookie('laravel_token');


        //Passport::useTokenModel(Token::class);

        Gate::define('f_admin', function(Usuario $user){

            $administrativo = PermissoesModel::where('cpf', $user->cpf)->first();
            if($administrativo != null && $administrativo->f_admin == '1')
            {
                return true;
            }
            return false;

        });

        Gate::define('f_desenvolvedor', function(Usuario $user){

            $administrativo = PermissoesModel::where('cpf', $user->cpf)->first();
            if($administrativo != null && $administrativo->f_desenvolvedor == '1')
            {
                return true;
            }
            return false;

        });

        Gate::define('f_usuario', function(Usuario $user){

            $administrativo = PermissoesModel::where('cpf', $user->cpf)->first();
            if($administrativo != null && $administrativo->f_usuario == '1')
            {
                return true;
            }
            return false;

        });

        Gate::define('f_medico', function(Usuario $user){

            $administrativo = PermissoesModel::where('cpf', $user->cpf)->first();
            if($administrativo != null && $administrativo->f_usuario == '1')
            {
                return true;
            }
            return false;

        });
    }


}
