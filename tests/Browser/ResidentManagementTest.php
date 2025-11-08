<?php

namespace Tests\Browser;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResidentManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_resident_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        Resident::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/residents')
                ->assertSee('Residents')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_create_resident(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/residents')
                ->clickLink('New')
                ->waitForText('Create Resident')
                ->type('data.nik', '3201012345678901')
                ->type('data.name', 'John Doe Test')
                ->type('data.no_kk', '3201010101010001')
                ->type('data.address', 'Jl. Test No. 123')
                ->type('data.phone_number', '081234567890')
                ->select('data.status', 'tetap')
                ->press('Create')
                ->waitForText('John Doe Test')
                ->assertSee('3201012345678901');
        });
    }

    public function test_admin_can_edit_resident(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $resident = Resident::factory()->create([
            'name' => 'Original Name',
            'phone_number' => '081111111111',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/residents')
                ->waitForText('Original Name')
                ->click('tr:has-text("Original Name") button[title="Edit"]')
                ->waitForText('Edit Resident')
                ->type('data.name', 'Updated Name')
                ->type('data.phone_number', '089999999999')
                ->press('Save changes')
                ->waitForText('Updated Name')
                ->assertSee('089999999999');
        });
    }

    public function test_admin_can_delete_resident(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $resident = Resident::factory()->create([
            'name' => 'To Be Deleted',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/residents')
                ->waitForText('To Be Deleted')
                ->click('tr:has-text("To Be Deleted") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('To Be Deleted');
        });
    }

    public function test_warga_can_view_resident_list(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        Resident::factory()->count(2)->create();

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/residents')
                ->assertSee('Residents')
                ->assertPresent('table');
        });
    }
}
