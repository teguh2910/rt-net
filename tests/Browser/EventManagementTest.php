<?php

namespace Tests\Browser;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EventManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_event_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        Event::factory()->count(3)->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/events')
                ->assertSee('Acara & Kegiatan')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_create_event(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/events')
                ->clickLink('New')
                ->waitForText('Create Event')
                ->type('data.name', 'Gotong Royong Test')
                ->type('data.description', 'Kegiatan gotong royong bulanan')
                ->type('data.location', 'Balai RT 03')
                ->type('data.organizer', 'Pengurus RT 03')
                ->press('Create')
                ->waitForText('Gotong Royong Test')
                ->assertSee('Gotong Royong Test');
        });
    }

    public function test_ketua_rt_can_create_event(): void
    {
        $ketua = User::factory()->create([
            'role' => 'ketua_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua)
                ->visit('/admin/events')
                ->assertSee('New')
                ->clickLink('New')
                ->waitForText('Create Event')
                ->type('data.name', 'Pengajian Rutin')
                ->type('data.description', 'Pengajian bulanan RT')
                ->type('data.location', 'Masjid Al-Ikhlas')
                ->type('data.organizer', 'Ketua RT 03')
                ->press('Create')
                ->waitForText('Pengajian Rutin')
                ->assertSee('Pengajian Rutin');
        });
    }

    public function test_warga_can_view_but_not_create_event(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        Event::factory()->count(2)->create([
            'created_by' => User::factory()->create(['role' => 'admin_rt']),
        ]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/events')
                ->assertSee('Acara & Kegiatan')
                ->assertPresent('table')
                ->assertDontSee('New');
        });
    }

    public function test_admin_can_edit_event(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $event = Event::factory()->create([
            'created_by' => $admin->id,
            'name' => 'Original Event',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/events')
                ->waitForText('Original Event')
                ->click('tr:has-text("Original Event") button[title="Edit"]')
                ->waitForText('Edit Event')
                ->type('data.name', 'Updated Event Name')
                ->press('Save changes')
                ->waitForText('Updated Event Name')
                ->assertSee('Updated Event Name');
        });
    }

    public function test_admin_can_delete_event(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $event = Event::factory()->create([
            'created_by' => $admin->id,
            'name' => 'Event To Delete',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/events')
                ->waitForText('Event To Delete')
                ->click('tr:has-text("Event To Delete") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('Event To Delete');
        });
    }
}
