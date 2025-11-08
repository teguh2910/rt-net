<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_login_as_admin_rt(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user, 'web')
                ->visit('/admin')
                ->pause(500)
                ->assertPathIs('/admin');
        });
    }

    public function test_user_can_login_as_ketua_rt(): void
    {
        $user = User::factory()->create([
            'email' => 'ketua@test.com',
            'password' => bcrypt('password'),
            'role' => 'ketua_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user, 'web')
                ->visit('/admin')
                ->pause(500)
                ->assertPathIs('/admin');
        });
    }

    public function test_user_can_login_as_bendahara(): void
    {
        $user = User::factory()->create([
            'email' => 'bendahara@test.com',
            'password' => bcrypt('password'),
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user, 'web')
                ->visit('/admin')
                ->pause(500)
                ->assertPathIs('/admin');
        });
    }

    public function test_user_can_login_as_warga(): void
    {
        $user = User::factory()->create([
            'email' => 'warga@test.com',
            'password' => bcrypt('password'),
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user, 'web')
                ->visit('/admin')
                ->pause(500)
                ->assertPathIs('/admin');
        });
    }
}
