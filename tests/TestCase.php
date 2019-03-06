<?php

namespace Bitfumes\Multiauth\Tests;

use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\MultiauthServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class TestCase extends BaseTestCase
{
    public function setup():void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->withFactories(__DIR__ . '/../src/factories');
        app()->register(LaravelServiceProvider::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('jwt.secret', 'abcdef');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('multiauth.registration_notification_email', true);
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [MultiauthServiceProvider::class];
    }

    public function logInAdmin($args = [])
    {
        $admin = $this->createAdmin($args);
        $this->actingAs($admin, 'admin');

        return $admin;
    }

    public function createAdmin($args = [], $num=null)
    {
        return factory(Admin::class, $num)->create($args);
    }

    public function loginSuperAdmin($args = [])
    {
        $super = factory(Admin::class)->create($args);
        $role  = factory(Role::class)->create(['name'=>'super']);
        $super->roles()->attach($role);
        $this->actingAs($super, 'admin');

        return $super;
    }
}
