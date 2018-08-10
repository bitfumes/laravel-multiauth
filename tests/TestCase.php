<?php

namespace Bitfumes\Multiauth\Tests;

use Bitfumes\Multiauth\MultiauthServiceProvider;
use Bitfumes\Multiauth\Model\Admin as AdminModel;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->withFactories(__DIR__.'/../src/database/factories');
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
    }

    public function createAdmin()
    {
        (new Admin())->create();

        return AdminModel::first();
    }
}

class Admin extends AdminModel
{
    public function create($args = [])
    {
        $this->name = 'Sarthak';
        $this->email = 'sarthak@bitfumes.com';
        $this->password = bcrypt('secret');
        $this->remember_token = 'asdfasdfasd';
        $this->save();

        return $this;
    }
}
