<?php

namespace Tests\Feature;

use App\Models\DigitalLetter;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DigitalLetterManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_digital_letter(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $resident = Resident::factory()->create();

        $this->actingAs($admin);

        $letterData = [
            'letter_type' => 'domisili',
            'letter_number' => 'SK/001/RT03/2025',
            'resident_id' => $resident->id,
            'letter_content' => 'Surat Keterangan Domisili',
            'issued_date' => now(),
            'valid_until' => now()->addMonths(6),
            'issued_by' => $admin->id,
        ];

        $letter = DigitalLetter::create($letterData);

        $this->assertDatabaseHas('digital_letters', [
            'letter_type' => 'domisili',
            'letter_number' => 'SK/001/RT03/2025',
            'resident_id' => $resident->id,
        ]);

        $this->assertEquals('domisili', $letter->letter_type);
    }

    public function test_letter_number_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        DigitalLetter::factory()->create([
            'letter_number' => 'SK/001/RT03/2025',
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        DigitalLetter::factory()->create([
            'letter_number' => 'SK/001/RT03/2025',
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);
    }

    public function test_digital_letter_belongs_to_resident(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $this->assertInstanceOf(Resident::class, $letter->resident);
        $this->assertEquals($resident->id, $letter->resident->id);
    }

    public function test_digital_letter_has_issuer(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $this->assertInstanceOf(User::class, $letter->issuedBy);
        $this->assertEquals($admin->id, $letter->issuedBy->id);
    }

    public function test_digital_letter_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $this->actingAs($admin);

        $letter->update([
            'letter_content' => 'Updated content',
            'valid_until' => now()->addYear(),
        ]);

        $this->assertDatabaseHas('digital_letters', [
            'id' => $letter->id,
            'letter_content' => 'Updated content',
        ]);
    }

    public function test_digital_letter_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $this->actingAs($admin);

        $letter->delete();

        $this->assertDatabaseMissing('digital_letters', [
            'id' => $letter->id,
        ]);
    }

    public function test_can_filter_letters_by_type(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();

        DigitalLetter::factory()->count(3)->create([
            'letter_type' => 'domisili',
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        DigitalLetter::factory()->count(2)->create([
            'letter_type' => 'usaha',
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $domisiliLetters = DigitalLetter::where('letter_type', 'domisili')->get();

        $this->assertCount(3, $domisiliLetters);
    }

    public function test_can_get_letters_for_resident(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident1 = Resident::factory()->create();
        $resident2 = Resident::factory()->create();

        DigitalLetter::factory()->count(2)->create([
            'resident_id' => $resident1->id,
            'issued_by' => $admin->id,
        ]);

        DigitalLetter::factory()->count(3)->create([
            'resident_id' => $resident2->id,
            'issued_by' => $admin->id,
        ]);

        $resident1Letters = DigitalLetter::where('resident_id', $resident1->id)->get();

        $this->assertCount(2, $resident1Letters);
    }
}
