<?php

namespace Bitfumes\Multiauth\Tests;

use Carbon\Carbon;
use Bitfumes\Multiauth\MultiauthServiceProvider;
use Bitfumes\Multiauth\Model\Admin as AdminModel;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->withFactories(__DIR__.'/../src/Database/factories');

        // $this->loadMigration();
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
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

    /** @test */
    public function it_runs_the_migrations()
    {
        $now = Carbon::now();
        \DB::table('admins')->insert([
            'name' => 'Orchestra',
            'email' => 'hello@orchestraplatform.com',
            'password' => \Hash::make('456'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $users = \DB::table('admins')->where('id', '=', 1)->first();
        $this->assertEquals('hello@orchestraplatform.com', $users->email);
        $this->assertTrue(\Hash::check('456', $users->password));
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
