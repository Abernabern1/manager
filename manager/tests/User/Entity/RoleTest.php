<?php

namespace App\Tests\User\Entity;

use App\Model\User\Entity\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testError(): void
    {
        $this->expectExceptionMessage('Given role not found.');

        new Role('role');
    }

    public function testSuccess(): void
    {
        $role = new Role(Role::USER);

        $this->assertEquals(Role::USER, $role->getName());
    }
}
