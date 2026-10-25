<?php

namespace Modules\Acl\tests\Feature;

use LdapRecord\Testing\DirectoryFake;
use Modules\Acl\app\Ldap\LdapCustomUser;
use Modules\SystemBase\tests\TestCase;

class LdapUser extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        DirectoryFake::setup();
    }

    public function test_user_can_login_via_ldap(): void
    {
        // Create a Fake-LDAP-User
        $ldapUser = LdapCustomUser::create([
            'cn' => 'peter',
            'mail' => 'peter@example.com',
            'memberof' => ['cn=members,ou=groups,dc=ldap,dc=local'],
        ]);

        $response = $this->post('/login', [
            'username' => 'peter',
            'password' => '1234567',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'peter@example.com']);
    }
}
