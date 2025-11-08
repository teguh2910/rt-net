<?php

namespace Tests\Browser;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AnnouncementManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_announcement_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        Announcement::factory()->count(3)->create([
            'created_by' => $admin->id,
            'published_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/announcements')
                ->assertSee('Announcements')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_create_announcement(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/announcements')
                ->clickLink('New')
                ->waitForText('Create Announcement')
                ->type('data.title', 'Pengumuman Test Browser')
                ->type('data.content', 'Ini adalah konten pengumuman dari browser test')
                ->check('data.is_published')
                ->press('Create')
                ->waitForText('Pengumuman Test Browser')
                ->assertSee('Pengumuman Test Browser');
        });
    }

    public function test_ketua_rt_can_create_announcement(): void
    {
        $ketua = User::factory()->create([
            'role' => 'ketua_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua)
                ->visit('/admin/announcements')
                ->clickLink('New')
                ->waitForText('Create Announcement')
                ->type('data.title', 'Pengumuman Ketua RT')
                ->type('data.content', 'Konten dari Ketua RT')
                ->check('data.is_published')
                ->press('Create')
                ->waitForText('Pengumuman Ketua RT')
                ->assertSee('Pengumuman Ketua RT');
        });
    }

    public function test_warga_cannot_create_announcement(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/announcements')
                ->assertDontSee('New');
        });
    }

    public function test_admin_can_edit_announcement(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $announcement = Announcement::factory()->create([
            'created_by' => $admin->id,
            'title' => 'Original Title',
            'published_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/announcements')
                ->waitForText('Original Title')
                ->click('tr:has-text("Original Title") button[title="Edit"]')
                ->waitForText('Edit Announcement')
                ->type('data.title', 'Updated Title')
                ->press('Save changes')
                ->waitForText('Updated Title')
                ->assertSee('Updated Title');
        });
    }

    public function test_admin_can_delete_announcement(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $announcement = Announcement::factory()->create([
            'created_by' => $admin->id,
            'title' => 'To Be Deleted',
            'published_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/announcements')
                ->waitForText('To Be Deleted')
                ->click('tr:has-text("To Be Deleted") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('To Be Deleted');
        });
    }
}
