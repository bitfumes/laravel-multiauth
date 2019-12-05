<?php

namespace Bitfumes\Multiauth;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Bitfumes\Multiauth\Console\Commands\RoleCmd;
use Bitfumes\Multiauth\Console\Commands\SeedCmd;
use Bitfumes\Multiauth\Exception\MultiAuthHandler;
use Bitfumes\Multiauth\Providers\AuthServiceProvider;
use Bitfumes\Multiauth\Console\Commands\PermissionSeed;
use Bitfumes\Multiauth\Console\Commands\MakeMultiAuthCommand;
use Bitfumes\Multiauth\Console\Commands\RollbackMultiAuthCommand;
use Bitfumes\Multiauth\Http\Middleware\redirectIfAuthenticatedAdmin;

class MultiauthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->canHaveAdminBackend()) {
            $this->registerRoutes();
            $this->publisheThings();
            $this->mergeAuthFileFrom(__DIR__ . '/../config/auth.php', 'auth');
            $this->mergeConfigFrom(__DIR__ . '/../config/multiauth.php', 'multiauth');
            $this->loadAdminCommands();
        }
        $this->loadCommands();
    }

    public function register()
    {
        if ($this->canHaveAdminBackend()) {
            if (app()->environment() == 'testing') {
                $this->loadFactories();
            }
            $this->loadMiddleware();
            $this->registerExceptionHandler();
            app()->register(AuthServiceProvider::class);
        }
    }

    protected function loadFactories()
    {
        $appFactories = scandir(database_path('/factories'));
        $factoryPath  = !in_array('AdminFactory.php', $appFactories) ? __DIR__ . '/factories' : database_path('/factories');

        $this->app->make(Factory::class)->load($factoryPath);
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        });
    }

    /**
     * Get the Blogg route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'namespace'  => "Bitfumes\Multiauth\Http\Controllers",
            'middleware' => 'api',
            'prefix'     => 'api/' . config('multiauth.prefix', 'admin'),
        ];
    }

    protected function loadRoutesFrom($path)
    {
        $prefix   = config('multiauth.prefix', 'admin');
        $routeDir = base_path('routes');
        if (file_exists($routeDir)) {
            $appRouteDir = scandir($routeDir);
            if (!$this->app->routesAreCached()) {
                require in_array("{$prefix}.php", $appRouteDir) ? base_path("routes/{$prefix}.php") : $path;
            }
        }

        require $path;
    }

    protected function loadMiddleware()
    {
        app('router')->aliasMiddleware('admin', redirectIfAuthenticatedAdmin::class);
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
        $prefix = config('multiauth.prefix', 'admin');
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ], 'multiauth:migrations');
        $this->publishes([
            __DIR__ . '/database/factories' => database_path('factories'),
        ], 'multiauth:factories');
        $this->publishes([
            __DIR__ . '/../config/multiauth.php' => config_path('multiauth.php'),
        ], 'multiauth:config');
        $this->publishes([
            __DIR__ . '/routes/routes.php' => base_path("routes/{$prefix}.php"),
        ], 'multiauth:routes');
    }

    protected function loadAdminCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SeedCmd::class,
                RoleCmd::class,
            ]);
        }
    }

    protected function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeMultiAuthCommand::class,
                RollbackMultiAuthCommand::class,
                PermissionSeed::class,
            ]);
        }
    }

    protected function canHaveAdminBackend()
    {
        return config('multiauth.admin_active', true);
    }
}
