<?php

namespace Bitfumes\Multiauth\Tests;

use Bitfumes\Multiauth\MultiauthServiceProvider;
use Bitfumes\Multiauth\Model\Admin;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->withFactories(__DIR__.'/../src/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [MultiauthServiceProvider::class];
    }

    public function logInAdmin($args = [])
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'admin');
        return $admin;
    }

    public function createAdmin()
    {
        return factory(Admin::class)->create();
        // return AdminFactory::create();
    }

    public function makeAdmin()
    {
        return AdminFactory::make();
    }
}

class AdminFactory
{
    public static function make()
    {
        $faker = \Faker\Factory::create();
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10)
        ];
    }

    public static function create()
    {
        return Admin::create(self::make());
    }
}

class RoleFactory
{
    public function makeSuper()
    {
        $faker = \Faker\Factory::create();
        return [
            'name' => 'super'
        ];
    }

    public function makeEditor()
    {
        $faker = \Faker\Factory::create();
        return [
            'name' => 'super'
        ];
    }

    public function create(Type $var = null)
    {
        // code...
    }
}
