<?php

namespace Bitfumes\Multiauth\Tests\Unit;

use Bitfumes\Multiauth\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminPolicyTest extends TestCase
{
    /**
     * @test
     */
    public function an_admin_can_check_if_he_is_superAdmin()
    {
        $admin = $this->createAdmin();
        dd($admin->can('isSuperAdmin'));
        $this->assertInstanceOf(BelongsToMany::class, $admin->roles());
    }
}
