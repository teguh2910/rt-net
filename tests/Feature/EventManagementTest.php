<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_event(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $eventData = [
            'name' => 'Gotong Royong Bulanan',
            'description' => 'Kegiatan kebersihan lingkungan RT',
            'event_date' => now()->addDays(7),
            'location' => 'Balai RT 03',
            'organizer' => 'Pengurus RT 03',
            'send_notification' => true,
            'created_by' => $admin->id,
        ];

        $event = Event::create($eventData);

        $this->assertDatabaseHas('events', [
            'name' => 'Gotong Royong Bulanan',
            'location' => 'Balai RT 03',
            'created_by' => $admin->id,
        ]);

        $this->assertTrue($event->send_notification);
    }

    public function test_event_has_creator(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $event = Event::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $event->createdBy);
        $this->assertEquals($user->id, $event->createdBy->id);
    }

    public function test_event_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $event = Event::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $newDate = now()->addDays(14);
        $event->update([
            'name' => 'Updated Event Name',
            'event_date' => $newDate,
            'location' => 'New Location',
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event Name',
            'location' => 'New Location',
        ]);
    }

    public function test_event_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $event = Event::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $event->delete();

        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    public function test_can_get_upcoming_events(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Event::factory()->count(3)->create([
            'created_by' => $user->id,
            'event_date' => now()->addDays(5),
        ]);

        Event::factory()->count(2)->create([
            'created_by' => $user->id,
            'event_date' => now()->subDays(5),
        ]);

        $upcomingEvents = Event::where('event_date', '>=', now())->get();

        $this->assertCount(3, $upcomingEvents);
    }

    public function test_can_get_past_events(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Event::factory()->count(4)->create([
            'created_by' => $user->id,
            'event_date' => now()->subDays(10),
        ]);

        Event::factory()->count(2)->create([
            'created_by' => $user->id,
            'event_date' => now()->addDays(5),
        ]);

        $pastEvents = Event::where('event_date', '<', now())->get();

        $this->assertCount(4, $pastEvents);
    }

    public function test_event_notification_flag_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);

        $event = Event::factory()->create([
            'created_by' => $admin->id,
            'send_notification' => true,
        ]);

        $this->assertTrue($event->send_notification);
    }

    public function test_event_date_casts_properly(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $eventDate = now()->addDays(7);

        $event = Event::factory()->create([
            'created_by' => $user->id,
            'event_date' => $eventDate,
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $event->event_date);
    }
}
