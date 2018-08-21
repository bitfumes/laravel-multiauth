<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class RollbackMultiAuthCommand extends Command
{
    protected $name;

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
        $this->stub_path = __DIR__.'/../../../stubs';
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
        if (! $this->checkGuard()) {
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

    protected function removeFactory()
    {
        $factory = database_path("factories/{$this->parseName()['{{singularClass}}']}Factory.php");
        unlink($factory);

        return $this;
    }

    protected function removeModel()
    {
        $model = app_path($this->parseName()['{{singularClass}}'].'.php');
        unlink($model);

        return $this;
    }

    protected function removeMiddleware()
    {
        $guard = $this->parseName()['{{singularClass}}'];
        $path = app_path('Http/Middleware');
        unlink("{$path}/RedirectIf{$guard}.php");
        unlink("{$path}/RedirectIfNot{$guard}.php");

        return $this;
    }

    protected function unRegisterMiddleware()
    {
        $kernel = file_get_contents(app_path('Http/Kernel.php'));
        $guard = $this->parseName()['{{singularSnake}}'];
        preg_match_all("/'{$guard}.+::class,\s+/", $kernel, $match);
        foreach ($match[0] as $match) {
            $kernel = str_replace($match, '', $kernel);
        }

        file_put_contents(app_path('Http/Kernel.php'), $kernel);

        return $this;
    }

    protected function removeMigration()
    {
        $guard = $this->parseName()['{{pluralSlug}}'];
        $migration_path = database_path('migrations');
        $files = glob("{$migration_path}/*.php");
        foreach ($files as $file) {
            if (str_contains($file, "create_{$guard}_table")) {
                unlink($file);
            }
        }

        return $this;
    }

    protected function rollbackViews()
    {
        $guard = $this->parseName()['{{singularClass}}'];
        $views_path = resource_path("views/{$guard}");
        $dirs = ['/auth/passwords/', '/auth/', '/layouts/', '/'];
        foreach ($dirs as $dir) {
            array_map('unlink', glob("{$views_path}{$dir}*.php"));
            rmdir($views_path.$dir);
        }

        return $this;
    }

    protected function rollbackControllers()
    {
        try {
            $guard = $this->parseName()['{{singularClass}}'];
            $path = app_path("/Http/Controllers/{$guard}");
            array_map('unlink', glob("{$path}/Auth/*.php"));
            array_map('unlink', glob("{$path}/*.php"));
            rmdir("$path/Auth");
            rmdir($path);
        } catch (Exception $ex) {
            throw new \RuntimeException($ex->getMessage());
        }

        return $this;
    }

    protected function checkGuard()
    {
        $guards = array_keys(config('auth.guards'));

        return in_array($this->name, $guards);
    }

    protected function rollbackRoutes()
    {
        unlink(base_path("routes/{$this->name}.php"));

        return $this;
    }

    protected function unRegisterRoutes()
    {
        $provider_path = app_path('Providers/RouteServiceProvider.php');
        $provider = file_get_contents($provider_path);

        $stubs = [
            $this->stub_path.'/routes/map.stub',
            $this->stub_path.'/routes/map_call.stub',
        ];
        foreach ($stubs as $stub) {
            $map = file_get_contents($stub);
            $map = strtr($map, $this->parseName());
            $provider = str_replace($map, '', $provider);
        }

        file_put_contents($provider_path, $provider);

        return $this;
    }

    protected function unPublishGuard()
    {
        $auth = file_get_contents(config_path('auth.php'));
        $guard = $this->parseName()['{{singularSnake}}'];

        for ($i = 0; $i < 3; $i++) {
            $m = preg_match_all("/'{$guard}s?'\s*=>\s*\[\n(.*\n){2}.*(\n.*)?\],/", $auth, $match);
            $auth = str_replace($match[0][0], '', $auth);
        }
        $auth = file_put_contents(config_path('auth.php'), $auth);

        return $this;
    }

    protected function removeNotification()
    {
        $name = $this->parseName()['{{singularClass}}'];
        $notification_path = app_path("/Notifications/{$name}");
        unlink("{$notification_path}/{$name}ResetPassword.php");
        rmdir($notification_path);

        return $this;
    }

    /**
     * Parse guard name
     * Get the guard name in different cases.
     * @param string $name
     * @return array
     */
    protected function parseName($name = null)
    {
        if (! $name) {
            $name = $this->name;
        }

        return $parsed = [
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
     * @return string
     */
    protected function getNamespace()
    {
        $namespace = Container::getInstance()->getNamespace();

        return rtrim($namespace, '\\');
    }
}
