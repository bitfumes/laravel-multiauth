<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Tests\TestCase;
use Bitfumes\Multiauth\Model\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends TestCase
{
    /**
    * @test
    */
    public function it_has_admin_conected_to_any_role()
    {
        $role = factory(Role::class)->create();
        $this->assertInstanceOf(BelongsToMany::class, $role->admins());
    }
}
