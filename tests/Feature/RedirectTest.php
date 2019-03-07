<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;

class RedirectTest extends TestCase
{
    /** @test */
    public function it_will_redirect_after_login_to_route_define_in_config()
    {
        app()['config']->set('multiauth.redirect_after_login', '/newPath');

        $admin = $this->createAdmin();
        $res   = $this->post(route('admin.login'), ['email'=>$admin->email, 'password'=>'secret123']);
        $res->assertRedirect('/newPath');
    }
}
