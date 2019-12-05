<?php

namespace Bitfumes\Multiauth\Tests\Feature;

use Bitfumes\Multiauth\Tests\TestCase;

class AdminGetTest extends TestCase
{
    /** @test */
    public function api_can_give_all_the_admin_requested_by_super_admin()
    {
        $this->loginSuperAdmin();
        $this->createAdmin();
        $res = $this->getJson(route('admin.all'))->assertSuccessful()->json();
        $this->assertEquals(2, count($res['data']));
    }

    /** @test */
    public function normal_admin_can_not_request_form_all_Admin_list()
    {
        $this->logInAdmin();
        $this->withExceptionHandling();
        $this->createAdmin();
        $this->getJson(route('admin.all'))->assertStatus(403);
    }

    /** @test */
    public function api_can_give_logged_in_admin_details()
    {
        $admin = $this->logInAdmin();
        $this->withExceptionHandling();
        $res   = $this->getJson(route('admin.me'))->assertStatus(202)->json();
        $this->assertEquals($admin->name, $res['name']);
    }
}
