<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class RollbackMultiAuthCommand extends Command
{
    protected $name;
    protected $stub_path;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:rollback 
                                {name=student : Give a name for guard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback everything for Scaffoldings for any guard you have created';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->stub_path = __DIR__ . '/../../../stubs';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name = $this->argument('name');
        $this->rollback();
    }

    protected function rollback()
    {
        if (!$this->checkGuard()) {
            $this->error("Guard {$this->name} does't exist");

            return;
        }
        $this->unPublishGuard()
             ->rollbackControllers()
             ->rollbackRoutes()
             ->unRegisterRoutes()
             ->rollbackViews()
             ->removeFactory()
             ->removeMigration()
             ->removeModel()
             ->removeMiddleware()
             ->unRegisterMiddleware()
             ->removeNotification();
    }

    protected function unPublishGuard()
    {
        $auth  = file_get_contents(config_path('auth.php'));
        $guard = $this->parseName()['{{singularSnake}}'];

        for ($i = 0; $i < 3; $i++) {
            preg_match_all("/'{$guard}s?'\s*=>\s*\[\n(.*\n){2}.*(\n.*)?\],/", $auth, $match);
            $auth = str_replace($match[0][0], '', $auth);
        }
        file_put_contents(config_path('auth.php'), $auth);
        $this->error("Step 1. {$guard} Guard is removed from config/auth.php file \n");

        return $this;
    }

    protected function rollbackControllers()
    {
        try {
            $guard = $this->parseName()['{{singularClass}}'];
            $path  = app_path("/Http/Controllers/{$guard}");
            array_map('unlink', glob("{$path}/Auth/*.php"));
            array_map('unlink', glob("{$path}/*.php"));
            rmdir("$path/Auth");
            rmdir($path);
        } catch (\Exception $ex) {
            throw new \RuntimeException($ex->getMessage());
        }
        $this->error("Step 2.  Controllers for {$guard} is rollbacked from App\Http\Controller\Student \n");

        return $this;
    }

    protected function rollbackRoutes()
    {
        try {
            unlink(base_path("routes/{$this->name}.php"));
            $this->error("Step 3. Routes for {$this->name} is rollbacked from routes directory \n");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $this;
    }

    protected function unRegisterRoutes()
    {
        $provider_path = app_path('Providers/RouteServiceProvider.php');
        $provider      = file_get_contents($provider_path);

        $stubs = [
            $this->stub_path . '/routes/map.stub',
            $this->stub_path . '/routes/map_call.stub',
        ];
        foreach ($stubs as $stub) {
            $map      = file_get_contents($stub);
            $map      = strtr($map, $this->parseName());
            $provider = str_replace($map, '', $provider);
        }

        file_put_contents($provider_path, $provider);
        $this->error("Step 4. Routes are UnRegistered in App\Provider\RouteServiceProvider.php \n");

        return $this;
    }

    protected function rollbackViews()
    {
        $guard      = $this->parseName()['{{singularClass}}'];
        $views_path = resource_path("views/{$guard}");
        $dirs       = ['/auth/passwords/', '/auth/', '/layouts/', '/'];
        foreach ($dirs as $dir) {
            array_map('unlink', glob("{$views_path}{$dir}*.php"));
            rmdir($views_path . $dir);
        }
        $this->error("Step 5. Views are removed from resources\\views\student directory \n");

        return $this;
    }

    protected function removeFactory()
    {
        $factory = database_path("factories/{$this->parseName()['{{singularClass}}']}Factory.php");

        try {
            unlink($factory);
            $this->error("Step 6. Factory for {$this->name} is removed from database\\factories directory \n");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $this;
    }

    protected function removeMigration()
    {
        $guard          = $this->parseName()['{{pluralSlug}}'];
        $migration_path = database_path('migrations');
        $files          = glob("{$migration_path}/*.php");
        foreach ($files as $file) {
            if (str_contains($file, "create_{$guard}_table")) {
                unlink($file);
            }
        }
        $this->error("Step 7. Migration for {$this->name} table schema is added to database\migrations \n");

        return $this;
    }

    protected function removeModel()
    {
        $model = app_path($this->parseName()['{{singularClass}}'] . '.php');
        unlink($model);
        $this->error("Step 8. Model for {$this->name} is removed from App\{$this->name}.php \n");

        return $this;
    }

    protected function removeMiddleware()
    {
        $guard = $this->parseName()['{{singularClass}}'];
        $path  = app_path('Http/Middleware');
        unlink("{$path}/RedirectIf{$guard}.php");
        unlink("{$path}/RedirectIfNot{$guard}.php");
        $this->error("Step 9. Middlewares related to {$this->name} is removed from App\Http\Middleware directory \n");

        return $this;
    }

    protected function unRegisterMiddleware()
    {
        $kernel = file_get_contents(app_path('Http/Kernel.php'));
        $guard  = $this->parseName()['{{singularSnake}}'];
        preg_match_all("/'{$guard}.+::class,\s+/", $kernel, $match);
        foreach ($match[0] as $match) {
            $kernel = str_replace($match, '', $kernel);
        }

        file_put_contents(app_path('Http/Kernel.php'), $kernel);
        $this->error("Step 10. Middleware for {$this->name} is Un-Registered from App\Http\Kernel.php file within routeMiddleware array \n");

        return $this;
    }

    protected function checkGuard()
    {
        $guards = array_keys(config('auth.guards'));

        return in_array($this->name, $guards);
    }

    protected function removeNotification()
    {
        $name              = $this->parseName()['{{singularClass}}'];
        $notification_path = app_path("/Notifications/{$name}");
        unlink("{$notification_path}/{$name}ResetPassword.php");
        unlink("{$notification_path}/{$name}VerifyEmail.php");
        rmdir($notification_path);
        $this->error("Step 11. Notification file for password reset is published at App\Notification\{$this->name}  directory \n");

        return $this;
    }

    /**
     * Parse guard name
     * Get the guard name in different cases.
     *
     * @param string $name
     *
     * @return array
     */
    protected function parseName($name = null)
    {
        if (!$name) {
            $name = $this->name;
        }

        return [
            '{{pluralCamel}}'   => str_plural(camel_case($name)),
            '{{pluralSlug}}'    => str_plural(str_slug($name)),
            '{{pluralSnake}}'   => str_plural(snake_case($name)),
            '{{pluralClass}}'   => str_plural(studly_case($name)),
            '{{singularCamel}}' => str_singular(camel_case($name)),
            '{{singularSlug}}'  => str_singular(str_slug($name)),
            '{{singularSnake}}' => str_singular(snake_case($name)),
            '{{singularClass}}' => str_singular(studly_case($name)),
            '{{namespace}}'     => $this->getNamespace(),
        ];
    }

    /**
     * Get project namespace
     * Default: App.
     *
     * @return string
     */
    protected function getNamespace()
    {
        $namespace = Container::getInstance()->getNamespace();

        return rtrim($namespace, '\\');
    }
}
