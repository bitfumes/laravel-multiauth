<?php

namespace Bitfumes\Multiauth\Tests;

use Bitfumes\Multiauth\Model\Role;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Permission;
use Bitfumes\Multiauth\MultiauthServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setup():void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
        $this->withFactories(__DIR__ . '/../src/database/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('multiauth.registration_notification_email', false);
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

    public function createAdmin($args = [])
    {
        return factory(Admin::class)->create($args);
    }

    public function create_permission($args = [], $num=null)
    {
        return factory(Permission::class, $num)->create($args);
    }

    public function loginSuperAdmin($args = [])
    {
        $super = factory(Admin::class)->create($args);
        $role  = factory(Role::class)->create(['name' => 'super']);
        $this->createAndLinkPermissionsTo($role);
        $super->roles()->attach($role);
        $this->actingAs($super, 'admin');

        return $super;
    }

    protected function createAndLinkPermissionsTo($role)
    {
        $models        = ['Admin', 'Role'];
        $tasks         = ['Create', 'Read', 'Update', 'Delete'];
        foreach ($tasks as $task) {
            foreach ($models as $model) {
                $name       = "{$task}{$model}";
                $permission = Permission::create(['name' => $name, 'parent'=>$model]);
                $role->addPermission([$permission->id]);
            }
        }
    }
}
