<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_announcement(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $announcementData = [
            'title' => 'Pengumuman Iuran Bulanan',
            'content' => 'Mohon untuk segera membayar iuran bulanan.',
            'published_at' => now(),
            'is_published' => true,
            'send_whatsapp' => false,
            'send_email' => false,
            'created_by' => $admin->id,
        ];

        $announcement = Announcement::create($announcementData);

        $this->assertDatabaseHas('announcements', [
            'title' => 'Pengumuman Iuran Bulanan',
            'is_published' => true,
            'created_by' => $admin->id,
        ]);

        $this->assertTrue($announcement->is_published);
    }

    public function test_ketua_rt_can_create_announcement(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua_rt', 'is_active' => true]);

        $this->actingAs($ketua);

        $announcement = Announcement::factory()->create([
            'created_by' => $ketua->id,
        ]);

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'created_by' => $ketua->id,
        ]);
    }

    public function test_announcement_has_creator(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $announcement = Announcement::factory()->create(['created_by' => $user->id, 'published_at' => now()]);

        $this->assertInstanceOf(User::class, $announcement->createdBy);
        $this->assertEquals($user->id, $announcement->createdBy->id);
    }

    public function test_announcement_can_be_published(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $announcement = Announcement::factory()->create([
            'created_by' => $admin->id,
            'is_published' => false,
        ]);

        $this->actingAs($admin);

        $announcement->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertTrue($announcement->fresh()->is_published);
        $this->assertNotNull($announcement->fresh()->published_at);
    }

    public function test_announcement_can_be_unpublished(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $announcement = Announcement::factory()->create([
            'created_by' => $admin->id,
            'is_published' => true,
        ]);

        $this->actingAs($admin);

        $announcement->update(['is_published' => false]);

        $this->assertFalse($announcement->fresh()->is_published);
    }

    public function test_announcement_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $announcement = Announcement::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $announcement->update([
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
    }

    public function test_announcement_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $announcement = Announcement::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $announcement->delete();

        $this->assertDatabaseMissing('announcements', [
            'id' => $announcement->id,
        ]);
    }

    public function test_can_get_published_announcements(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Announcement::factory()->count(3)->create([
            'created_by' => $user->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        Announcement::factory()->count(2)->create([
            'created_by' => $user->id,
            'is_published' => false,
            'published_at' => now(),
        ]);

        $published = Announcement::where('is_published', true)->get();

        $this->assertCount(3, $published);
    }

    public function test_announcement_can_have_expiry_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $expiryDate = now()->addDays(7);

        $announcement = Announcement::factory()->create([
            'created_by' => $admin->id,
            'expires_at' => $expiryDate,
        ]);

        $this->assertNotNull($announcement->expires_at);
        $this->assertEquals($expiryDate->format('Y-m-d'), $announcement->expires_at->format('Y-m-d'));
    }

    public function test_announcement_notification_flags_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);

        $announcement = Announcement::factory()->create([
            'created_by' => $admin->id,
            'send_whatsapp' => true,
            'send_email' => true,
        ]);

        $this->assertTrue($announcement->send_whatsapp);
        $this->assertTrue($announcement->send_email);
    }
}
