<?php

namespace Tests\Feature;

use App\Models\DigitalLetter;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DigitalLetterAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_can_only_view_their_own_digital_letters(): void
    {
        // Create a warga user with resident data
        $warga = User::factory()->create(['role' => 'warga']);
        $ownResident = Resident::factory()->create(['user_id' => $warga->id]);

        // Create digital letter for this warga
        $ownLetter = DigitalLetter::factory()->create(['resident_id' => $ownResident->id]);

        // Create another resident and their letter
        $otherResident = Resident::factory()->create();
        $otherLetter = DigitalLetter::factory()->create(['resident_id' => $otherResident->id]);

        // Warga can view their own letter
        $this->assertTrue($warga->can('view', $ownLetter));

        // Warga cannot view other resident's letter
        $this->assertFalse($warga->can('view', $otherLetter));
    }

    public function test_admin_can_view_all_digital_letters(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident1 = Resident::factory()->create();
        $resident2 = Resident::factory()->create();
        $letter1 = DigitalLetter::factory()->create(['resident_id' => $resident1->id]);
        $letter2 = DigitalLetter::factory()->create(['resident_id' => $resident2->id]);

        $this->assertTrue($admin->can('view', $letter1));
        $this->assertTrue($admin->can('view', $letter2));
    }

    public function test_ketua_rt_can_view_all_digital_letters(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua_rt']);
        $resident1 = Resident::factory()->create();
        $resident2 = Resident::factory()->create();
        $letter1 = DigitalLetter::factory()->create(['resident_id' => $resident1->id]);
        $letter2 = DigitalLetter::factory()->create(['resident_id' => $resident2->id]);

        $this->assertTrue($ketua->can('view', $letter1));
        $this->assertTrue($ketua->can('view', $letter2));
    }

    public function test_warga_cannot_create_digital_letter(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);

        $this->assertFalse($warga->can('create', DigitalLetter::class));
    }

    public function test_warga_cannot_update_digital_letter(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $resident = Resident::factory()->create(['user_id' => $warga->id]);
        $letter = DigitalLetter::factory()->create(['resident_id' => $resident->id]);

        $this->assertFalse($warga->can('update', $letter));
    }

    public function test_warga_cannot_delete_digital_letter(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $resident = Resident::factory()->create(['user_id' => $warga->id]);
        $letter = DigitalLetter::factory()->create(['resident_id' => $resident->id]);

        $this->assertFalse($warga->can('delete', $letter));
    }
}
