<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_warga_can_only_view_their_own_resident_data(): void
    {
        // Create a warga user with resident data
        $warga = User::factory()->create(['role' => 'warga']);
        $ownResident = Resident::factory()->create(['user_id' => $warga->id]);

        // Create another resident
        $otherResident = Resident::factory()->create();

        // Warga can view their own data
        $this->assertTrue($warga->can('view', $ownResident));

        // Warga cannot view other resident's data
        $this->assertFalse($warga->can('view', $otherResident));
    }

    public function test_admin_can_view_all_resident_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident1 = Resident::factory()->create();
        $resident2 = Resident::factory()->create();

        $this->assertTrue($admin->can('view', $resident1));
        $this->assertTrue($admin->can('view', $resident2));
    }

    public function test_ketua_rt_can_view_all_resident_data(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua_rt']);
        $resident1 = Resident::factory()->create();
        $resident2 = Resident::factory()->create();

        $this->assertTrue($ketua->can('view', $resident1));
        $this->assertTrue($ketua->can('view', $resident2));
    }

    public function test_warga_cannot_create_resident_data(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);

        $this->assertFalse($warga->can('create', Resident::class));
    }

    public function test_warga_cannot_update_resident_data(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $resident = Resident::factory()->create(['user_id' => $warga->id]);

        $this->assertFalse($warga->can('update', $resident));
    }

    public function test_warga_cannot_delete_resident_data(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $resident = Resident::factory()->create(['user_id' => $warga->id]);

        $this->assertFalse($warga->can('delete', $resident));
    }
}
