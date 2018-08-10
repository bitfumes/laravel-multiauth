<?php

namespace Bitfumes\Multiauth;

use Bitfumes\Multiauth\Console\Commands\Authname;
use Bitfumes\Multiauth\Exception\MultiAuthHandler;
use Bitfumes\Multiauth\Http\Middleware\redirectIfUnauthenticatedAdmin;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;

class MultiauthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'bitfumes');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publisheThings();
        $this->mergeAuthFileFrom(__DIR__.'/../config/auth.php', 'auth');
        $this->loadCommands();
    }

    public function register()
    {
        $this->loadFactories();
        $this->loadMiddleware();
        $this->registerExceptionHandler();
    }

    protected function loadFactories()
    {
        $factoryPath = __DIR__.'/database/factories/';
        $this->app->make(Factory::class)->load($factoryPath);
    }

    protected function loadMiddleware()
    {
        app('router')->aliasMiddleware('admin', redirectIfUnauthenticatedAdmin::class);
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
            __DIR__.'/Database/migrations/' => database_path('migrations'),
        ], 'multiauth:migrations');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/bitfumes/multiauth'),
        ], 'multiauth:views');
        $this->publishes([
            __DIR__.'/Database/factories' => database_path('factories'),
        ], 'multiauth:factory');
    }

    protected function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Authname::class,
            ]);
        }
    }
}
