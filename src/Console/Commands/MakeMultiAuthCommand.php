<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class MakeMultiAuthCommand extends Command
{
    protected $name;
    protected $stub_path;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:make
                                {name=student : Give a name for guard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic multilogin and registration system';

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
        if ($this->checkGuard()) {
            $this->error("Provider '{$this->name}' already exist");

            return;
        }
        $this->addGuard()
             ->publishControllers()
             ->publishRoutes()
             ->registerRoutes()
             ->loadViews()
             ->publishFactory()
             ->publishMigration()
             ->publishModel()
             ->publishMiddleware()
             ->registerMiddleware()
             ->publishNotification();
    }

    protected function addGuard()
    {
        $auth  = file_get_contents(config_path('auth.php'));
        $guard = $this->parseName()['{{singularClass}}'];

        $keys = [
            'guards' => [
                'to_replace' => "'guards' => [",
                'stub'       => file_get_contents("{$this->stub_path}/config/guards.stub"),
            ],
            'providers' => [
                'to_replace' => "'providers' => [",
                'stub'       => file_get_contents("{$this->stub_path}/config/providers.stub"),
            ],
            'passwords' => [
                'to_replace' => "'passwords' => [",
                'stub'       => file_get_contents("{$this->stub_path}/config/passwords.stub"),
            ],
        ];

        foreach ($keys as $key) {
            $compiled = strtr($key['stub'], $this->parseName());
            $auth     = str_replace($key['to_replace'], $key['to_replace'] . $compiled, $auth);
        }

        file_put_contents(config_path('auth.php'), $auth);
        $this->info("Step 1. {$guard} Guard is added to config/auth.php file \n");

        return $this;
    }

    protected function checkGuard()
    {
        $providers = array_keys(config('auth.providers'));
        $name      = str_plural($this->name);

        return in_array($name, $providers);
    }

    protected function publishControllers()
    {
        $stub_path = $this->stub_path . '/Controllers';
        $name_map  = $this->parseName();
        $guard     = $name_map['{{singularClass}}'];

        $files = [
            'Auth/ForgotPasswordController',
            'Auth/LoginController',
            'Auth/RegisterController',
            'Auth/ResetPasswordController',
            'Auth/VerificationController',
            'HomeController',
        ];

        $publish_path = app_path("/Http/Controllers/{$guard}");

        if (!is_dir($publish_path)) {
            mkdir($publish_path, 0755, true);
            mkdir("{$publish_path}/Auth", 0755, true);
        }

        foreach ($files as $file) {
            $stub     = file_get_contents("{$stub_path}/{$file}.stub");
            $complied = strtr($stub, $name_map);
            file_put_contents("{$publish_path}/{$file}.php", $complied);
        }
        $this->info("Step 2. New Controllers for {$guard} is added to App\Http\Controller\{$guard} \n");

        return $this;
    }

    protected function publishRoutes()
    {
        $stub_path    = $this->stub_path . '/routes';
        $name_map     = $this->parseName();
        $publish_path = base_path('routes');
        $guard        = $name_map['{{singularSlug}}'];

        if (app()->environment() == 'testing') {
            if (!file_exists(base_path('routes'))) {
                mkdir(base_path('routes'));
            }
        }

        if (file_exists("{$publish_path}/{$guard}.php")) {
            $this->error("{$guard}.php file already exists");
            if (!$this->confirm('Do you want to override ?')) {
                return;
            }
        }

        $stub     = file_get_contents("{$stub_path}/routes.stub");
        $complied = strtr($stub, $name_map);
        file_put_contents("{$publish_path}/{$guard}.php", $complied);
        $this->info("Step 3. Routes for {$guard} is added to routes/{$guard}.php file \n");

        return $this;
    }

    protected function registerRoutes()
    {
        $provider_path = app_path('Providers/RouteServiceProvider.php');

        $provider = file_get_contents($provider_path);
        $name_map = $this->parseName();

        // Function
        $stub = $this->stub_path . '/routes/map.stub';

        $map = file_get_contents($stub);
        $map = strtr($map, $name_map);
        if (strpos($provider, $map)) {
            $this->error('Routes are already registered');

            return;
        }

        preg_match('/\s+\/\*\*\n\s+\*\s(\w+\s)+"web"\s(\w+\s)+\w+.\n/', $provider, $match);
        $provider = str_replace($match[0], $map . $match[0], $provider);
        /********** Function Call **********/

        $map_call = file_get_contents($this->stub_path . '/routes/map_call.stub');

        $map_call = strtr($map_call, $name_map);

        $map_call_bait = '$this->mapWebRoutes();';

        $provider = str_replace($map_call_bait, $map_call_bait . $map_call, $provider);

        // Overwrite config file
        file_put_contents($provider_path, $provider);
        $this->info("Step 4. Step 3 generated route file is registered in App\Provider\RouteServiceProvider.php \n");

        return $this;
    }

    protected function loadViews()
    {
        $guard = $this->parseName()['{{singularSlug}}'];

        $views_path = resource_path('views/' . $guard);
        $stub_path  = "{$this->stub_path}/views";
        $views      = [
            'home.blade',
            'layouts/app.blade',
            'auth/login.blade',
            'auth/register.blade',
            'auth/passwords/email.blade',
            'auth/passwords/reset.blade',
        ];

        foreach ($views as $view) {
            $stub_content = file_get_contents("{$stub_path}/{$view}.stub");
            $complied     = strtr($stub_content, $this->parseName());
            $dir          = dirname("{$views_path}/{$view}");
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents("{$views_path}/{$view}.php", $complied);
        }
        $this->info("Step 5. Views are added to resources\\views\{$guard} directory \n");

        return $this;
    }

    protected function publishFactory()
    {
        $stub_content = file_get_contents("{$this->stub_path}/factory.stub");

        $compiled = strtr($stub_content, $this->parseName());

        $factory_path = database_path("factories/{$this->parseName()['{{singularClass}}']}Factory.php");

        file_put_contents($factory_path, $compiled);
        $this->info("Step 6. Factory for {$this->name} is added to database\\factories directory as {$this->parseName()['{{singularClass}}']}Factory.php \n");

        return $this;
    }

    protected function publishMigration()
    {
        $stub_content = file_get_contents("{$this->stub_path}/migration.stub");

        $compiled       = strtr($stub_content, $this->parseName());
        $signature      = date('Y_m_d_His');
        $migration_path = database_path("migrations/{$signature}_create_{$this->parseName()['{{pluralSnake}}']}_table.php");

        file_put_contents($migration_path, $compiled);
        $this->info("Step 7. Migration for {$this->name} table schema is added to database\migrations \n");

        return $this;
    }

    protected function publishModel()
    {
        $stub_content = file_get_contents("{$this->stub_path}/model.stub");
        $compiled     = strtr($stub_content, $this->parseName());
        $model_path   = app_path($this->parseName()['{{singularClass}}'] . '.php');

        file_put_contents($model_path, $compiled);
        $this->info("Step 8. Model for {$this->name} is added to App\\{$this->name}.php \n");

        return $this;
    }

    protected function publishMiddleware()
    {
        $middleware_path = app_path('Http/Middleware');

        $stub = file_get_contents($this->stub_path . '/Middleware/RedirectIfAuthenticated.stub');

        $guest_middleware = strtr($stub, $this->parseName());

        file_put_contents($middleware_path . '/RedirectIf' . $this->parseName()['{{singularClass}}'] . '.php', $guest_middleware);

        // ...

        $stub = file_get_contents($this->stub_path . '/Middleware/RedirectIfNotAuthenticated.stub');

        $middleware = strtr($stub, $this->parseName());

        file_put_contents($middleware_path . '/RedirectIfNot' . $this->parseName()['{{singularClass}}'] . '.php', $middleware);
        $this->info("Step 9. Middlewares related to {$this->name} is added App\Http\Middleware directory \n");

        return $this;
    }

    protected function registerMiddleware()
    {
        $kernel_path = app_path('Http/Kernel.php');

        $kernel = file_get_contents($kernel_path);

        // Route Middleware

        $route_middleware = file_get_contents($this->stub_path . '/Middleware/Kernel.stub');

        $route_middleware = strtr($route_middleware, $this->parseName());

        $route_middleware_bait = 'protected $routeMiddleware = [';

        $kernel = str_replace($route_middleware_bait, $route_middleware_bait . $route_middleware, $kernel);

        // Overwrite config file
        file_put_contents($kernel_path, $kernel);
        $this->info("Step 10. Above crated middleware in step 9 is registered in App\Http\Kernel.php file within routeMiddleware array \n");

        return $this;
    }

    protected function publishNotification()
    {
        $stub1 = file_get_contents($this->stub_path . '/Notifications/ResetPassword.stub');
        $stub2 = file_get_contents($this->stub_path . '/Notifications/VerifyEmail.stub');

        $notification1       = strtr($stub1, $this->parseName());
        $notification2       = strtr($stub2, $this->parseName());
        $name                = $this->parseName()['{{singularClass}}'];
        $notification_path1  = app_path("/Notifications/{$name}/{$name}ResetPassword.php");
        $notification_path2  = app_path("/Notifications/{$name}/{$name}VerifyEmail.php");

        $dir = dirname($notification_path1);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($notification_path1, $notification1);
        file_put_contents($notification_path2, $notification2);
        $this->info("Step 11. Notification file for password reset is published at App\Notification\\{$this->name} directory \n");

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
