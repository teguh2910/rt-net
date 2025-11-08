<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_resident(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $residentData = [
            'nik' => '3201012345678901',
            'name' => 'John Doe',
            'no_kk' => '3201010101010001',
            'address' => 'Jl. Merdeka No. 10',
            'phone_number' => '081234567890',
            'status' => 'tetap',
            'is_head_of_family' => true,
        ];

        $resident = Resident::create($residentData);

        $this->assertDatabaseHas('residents', [
            'nik' => '3201012345678901',
            'name' => 'John Doe',
            'status' => 'tetap',
        ]);

        $this->assertTrue($resident->is_head_of_family);
    }

    public function test_resident_nik_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);

        Resident::factory()->create(['nik' => '3201012345678901']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Resident::factory()->create(['nik' => '3201012345678901']);
    }

    public function test_resident_can_be_linked_to_user(): void
    {
        $user = User::factory()->create(['role' => 'warga']);
        $resident = Resident::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $resident->user);
        $this->assertEquals($user->id, $resident->user->id);
    }

    public function test_resident_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        $this->actingAs($admin);

        $resident->update([
            'phone_number' => '089876543210',
            'address' => 'Jl. Baru No. 5',
        ]);

        $this->assertDatabaseHas('residents', [
            'id' => $resident->id,
            'phone_number' => '089876543210',
            'address' => 'Jl. Baru No. 5',
        ]);
    }

    public function test_resident_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        $this->actingAs($admin);

        $resident->delete();

        $this->assertDatabaseMissing('residents', [
            'id' => $resident->id,
        ]);
    }

    public function test_can_filter_active_residents(): void
    {
        Resident::factory()->count(5)->create(['status' => 'tetap']);
        Resident::factory()->count(2)->create(['status' => 'kontrak']);

        $tetapResidents = Resident::where('status', 'tetap')->get();

        $this->assertCount(5, $tetapResidents);
    }

    public function test_can_get_head_of_family_residents(): void
    {
        Resident::factory()->count(3)->create(['is_head_of_family' => true]);
        Resident::factory()->count(5)->create(['is_head_of_family' => false]);

        $headOfFamilies = Resident::where('is_head_of_family', true)->get();

        $this->assertCount(3, $headOfFamilies);
    }

    public function test_resident_status_can_be_changed(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create(['status' => 'tetap']);

        $this->actingAs($admin);

        $resident->update(['status' => 'kontrak']);

        $this->assertEquals('kontrak', $resident->fresh()->status);
    }
}
