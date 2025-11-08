<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created_with_role(): void
    {
        $user = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin_rt',
            'is_active' => true,
        ]);
    }

    public function test_user_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create(['email' => 'test@example.com']);
    }

    public function test_user_can_be_active_or_inactive(): void
    {
        $activeUser = User::factory()->create(['is_active' => true]);
        $inactiveUser = User::factory()->create(['is_active' => false]);

        $this->assertTrue($activeUser->is_active);
        $this->assertFalse($inactiveUser->is_active);
    }

    public function test_user_roles_are_valid(): void
    {
        $adminRt = User::factory()->create(['role' => 'admin_rt']);
        $ketuaRt = User::factory()->create(['role' => 'ketua_rt']);
        $bendahara = User::factory()->create(['role' => 'bendahara']);
        $warga = User::factory()->create(['role' => 'warga']);

        $this->assertEquals('admin_rt', $adminRt->role);
        $this->assertEquals('ketua_rt', $ketuaRt->role);
        $this->assertEquals('bendahara', $bendahara->role);
        $this->assertEquals('warga', $warga->role);
    }

    public function test_can_get_active_users(): void
    {
        User::factory()->count(5)->create(['is_active' => true]);
        User::factory()->count(3)->create(['is_active' => false]);

        $activeUsers = User::where('is_active', true)->get();

        $this->assertCount(5, $activeUsers);
    }

    public function test_can_get_users_by_role(): void
    {
        User::factory()->count(2)->create(['role' => 'admin_rt']);
        User::factory()->count(3)->create(['role' => 'warga']);

        $admins = User::where('role', 'admin_rt')->get();
        $warga = User::where('role', 'warga')->get();

        $this->assertCount(2, $admins);
        $this->assertCount(3, $warga);
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(strlen($user->password) > 50);
    }

    public function test_user_can_be_updated(): void
    {
        $user = User::factory()->create();

        $user->update([
            'name' => 'Updated Name',
            'address' => 'New Address',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'address' => 'New Address',
        ]);
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_user_has_required_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'warga',
            'is_active' => true,
            'address' => 'Jl. Test No. 1',
        ]);

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
        $this->assertNotNull($user->role);
        $this->assertNotNull($user->is_active);
        $this->assertNotNull($user->address);
    }
}
