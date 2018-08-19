<?php

namespace Bitfumes\Multiauth;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Bitfumes\Multiauth\Console\Commands\RoleCmd;
use Bitfumes\Multiauth\Console\Commands\SeedCmd;
use Bitfumes\Multiauth\Exception\MultiAuthHandler;
use Bitfumes\Multiauth\Http\Middleware\redirectIfAuthenticatedAdmin;
use Bitfumes\Multiauth\Http\Middleware\redirectIfNotWithRoleOfAdmin;

class MultiauthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'multiauth');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->publisheThings();
        $this->mergeAuthFileFrom(__DIR__.'/../config/auth.php', 'auth');
        $this->mergeConfigFrom(__DIR__.'/../config/multiauth.php', 'multiauth');
        $this->loadCommands();
        $this->loadBladeSyntax();
    }

    public function register()
    {
        $this->loadFactories();
        $this->loadMiddleware();
        $this->registerExceptionHandler();
    }

    protected function loadFactories()
    {
        $appFactories = scandir(database_path('/factories'));
        $factoryPath = ! in_array('AdminFactory.php', $appFactories) ? __DIR__.'/factories' : database_path('/factories');

        $this->app->make(Factory::class)->load($factoryPath);
    }

    protected function loadMiddleware()
    {
        app('router')->aliasMiddleware('admin', redirectIfAuthenticatedAdmin::class);
        app('router')->aliasMiddleware('role', redirectIfNotWithRoleOfAdmin::class);
    }

    protected function registerExceptionHandler()
    {
        \App::singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            MultiAuthHandler::class
        );
    }

    protected function mergeAuthFileFrom($path, $key)
    {
        $original = $this->app['config']->get($key, []);
        $this->app['config']->set($key, $this->multi_array_merge(require $path, $original));
    }

    protected function multi_array_merge($toMerge, $original)
    {
        $auth = [];
        foreach ($original as $key => $value) {
            if (isset($toMerge[$key])) {
                $auth[$key] = array_merge($value, $toMerge[$key]);
            } else {
                $auth[$key] = $value;
            }
        }

        return $auth;
    }

    protected function publisheThings()
    {
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'multiauth:migrations');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/multiauth'),
        ], 'multiauth:views');
        $this->publishes([
            __DIR__.'/factories' => database_path('factories'),
        ], 'multiauth:factories');
        $this->publishes([
            __DIR__.'/../config/multiauth.php' => config_path('multiauth.php'),
        ], 'multiauth:config');
    }

    public function loadBladeSyntax()
    {
        Blade::if('admin', function ($role) {
            $roles = auth('admin')->user()->roles()->pluck('name');
            $match = count(array_intersect([$role, 'super'], $roles->toArray()));

            return (bool) $match;
            // return in_array($role, $roles->toArray());
        });
    }

    protected function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SeedCmd::class,
                RoleCmd::class,
            ]);
        }
    }
}
