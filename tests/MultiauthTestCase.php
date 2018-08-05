<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Bitfumes\Multiauth\Model\Admin as AdminModel;

abstract class MultiauthTestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function logInAdmin($args = [])
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'admin');
    }

    public function createAdmin()
    {
        (new Admin)->create();
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
