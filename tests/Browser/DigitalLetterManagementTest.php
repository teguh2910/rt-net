<?php

namespace Tests\Browser;

use App\Models\DigitalLetter;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DigitalLetterManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_digital_letter_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $resident = Resident::factory()->create();
        DigitalLetter::factory()->count(3)->create([
            'issued_by' => $admin->id,
            'resident_id' => $resident->id,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/digital-letters')
                ->assertSee('Digital Letters')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_create_digital_letter(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $resident = Resident::factory()->create([
            'name' => 'John Doe',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/digital-letters')
                ->clickLink('New')
                ->waitForText('Create Digital Letter')
                ->select('data.letter_type', 'domisili')
                ->type('data.letter_number', 'SK/001/RT03/2025')
                ->type('data.letter_content', 'Surat Keterangan Domisili untuk John Doe')
                ->press('Create')
                ->waitForText('SK/001/RT03/2025')
                ->assertSee('SK/001/RT03/2025');
        });
    }

    public function test_ketua_rt_can_create_digital_letter(): void
    {
        $ketua = User::factory()->create([
            'role' => 'ketua_rt',
            'is_active' => true,
        ]);

        Resident::factory()->create();

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua)
                ->visit('/admin/digital-letters')
                ->assertSee('New')
                ->clickLink('New')
                ->waitForText('Create Digital Letter')
                ->assertSee('Letter Type');
        });
    }

    public function test_warga_can_view_digital_letters(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();
        DigitalLetter::factory()->count(2)->create([
            'issued_by' => $admin->id,
            'resident_id' => $resident->id,
        ]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/digital-letters')
                ->assertSee('Digital Letters')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_edit_digital_letter(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'issued_by' => $admin->id,
            'resident_id' => $resident->id,
            'letter_number' => 'SK/TEST/RT03/2025',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/digital-letters')
                ->waitForText('SK/TEST/RT03/2025')
                ->click('tr:has-text("SK/TEST/RT03/2025") button[title="Edit"]')
                ->waitForText('Edit Digital Letter')
                ->type('data.letter_content', 'Updated Letter Content')
                ->press('Save changes')
                ->pause(1000)
                ->assertSee('SK/TEST/RT03/2025');
        });
    }

    public function test_admin_can_delete_digital_letter(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'issued_by' => $admin->id,
            'resident_id' => $resident->id,
            'letter_number' => 'SK/DELETE/RT03/2025',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/digital-letters')
                ->waitForText('SK/DELETE/RT03/2025')
                ->click('tr:has-text("SK/DELETE/RT03/2025") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('SK/DELETE/RT03/2025');
        });
    }
}
