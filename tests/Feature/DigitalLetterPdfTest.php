<?php

namespace Tests\Feature;

use App\Models\DigitalLetter;
use App\Models\Resident;
use App\Models\User;
use App\Services\DigitalLetterPdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DigitalLetterPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_pdf_service_can_generate_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $pdfService = new DigitalLetterPdfService;
        $pdfPath = $pdfService->generate($letter);

        $this->assertNotNull($pdfPath);
        Storage::disk('public')->assertExists($pdfPath);

        // Reload letter to check pdf_path was updated
        $letter->refresh();
        $this->assertNotNull($letter->pdf_path);
        $this->assertEquals($pdfPath, $letter->pdf_path);
    }

    public function test_pdf_service_generates_correct_filename(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
            'letter_number' => '001/RT/2025',
            'letter_type' => 'domisili',
        ]);

        $pdfService = new DigitalLetterPdfService;
        $pdfPath = $pdfService->generate($letter);

        $this->assertStringContainsString('surat-domisili-001-RT-2025.pdf', $pdfPath);
    }

    public function test_admin_can_preview_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('digital-letter.preview', $letter));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_download_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('digital-letter.download', $letter));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_warga_can_preview_their_own_letter_pdf(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $resident = Resident::factory()->create(['user_id' => $warga->id]);
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $response = $this->actingAs($warga)->get(route('digital-letter.preview', $letter));

        $response->assertStatus(200);
    }

    public function test_warga_cannot_preview_other_letter_pdf(): void
    {
        $warga = User::factory()->create(['role' => 'warga']);
        $ownResident = Resident::factory()->create(['user_id' => $warga->id]);

        $otherResident = Resident::factory()->create();
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $otherLetter = DigitalLetter::factory()->create([
            'resident_id' => $otherResident->id,
            'issued_by' => $admin->id,
        ]);

        $response = $this->actingAs($warga)->get(route('digital-letter.preview', $otherLetter));

        $response->assertStatus(403);
    }

    public function test_pdf_service_can_delete_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $resident = Resident::factory()->create();
        $letter = DigitalLetter::factory()->create([
            'resident_id' => $resident->id,
            'issued_by' => $admin->id,
        ]);

        $pdfService = new DigitalLetterPdfService;
        $pdfPath = $pdfService->generate($letter);

        Storage::disk('public')->assertExists($pdfPath);

        $deleted = $pdfService->delete($letter);

        $this->assertTrue($deleted);
        Storage::disk('public')->assertMissing($pdfPath);
    }
}
