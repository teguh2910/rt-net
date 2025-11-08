<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_document(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $documentData = [
            'title' => 'SK Pengurus RT 2025',
            'file_path' => 'documents/sk-pengurus-2025.pdf',
            'file_type' => 'pdf',
            'document_date' => now(),
            'description' => 'Surat Keputusan Pengurus RT periode 2025',
            'uploaded_by' => $admin->id,
        ];

        $document = Document::create($documentData);

        $this->assertDatabaseHas('documents', [
            'title' => 'SK Pengurus RT 2025',
            'file_type' => 'pdf',
            'uploaded_by' => $admin->id,
        ]);

        $this->assertEquals('pdf', $document->file_type);
    }

    public function test_document_has_uploader(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $document = Document::factory()->create(['uploaded_by' => $user->id]);

        $this->assertInstanceOf(User::class, $document->uploadedBy);
        $this->assertEquals($user->id, $document->uploadedBy->id);
    }

    public function test_document_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $document = Document::factory()->create(['uploaded_by' => $admin->id]);

        $this->actingAs($admin);

        $document->update([
            'title' => 'Updated Document Title',
            'description' => 'Updated description',
        ]);

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'title' => 'Updated Document Title',
            'description' => 'Updated description',
        ]);
    }

    public function test_document_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $document = Document::factory()->create(['uploaded_by' => $admin->id]);

        $this->actingAs($admin);

        $document->delete();

        $this->assertDatabaseMissing('documents', [
            'id' => $document->id,
        ]);
    }

    public function test_can_filter_documents_by_file_type(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Document::factory()->count(3)->create([
            'uploaded_by' => $user->id,
            'file_type' => 'pdf',
        ]);

        Document::factory()->count(2)->create([
            'uploaded_by' => $user->id,
            'file_type' => 'docx',
        ]);

        $pdfDocuments = Document::where('file_type', 'pdf')->get();

        $this->assertCount(3, $pdfDocuments);
    }

    public function test_can_get_recent_documents(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Document::factory()->count(5)->create([
            'uploaded_by' => $user->id,
            'document_date' => now()->subDays(1),
        ]);

        Document::factory()->count(3)->create([
            'uploaded_by' => $user->id,
            'document_date' => now()->subDays(30),
        ]);

        $recentDocuments = Document::where('document_date', '>=', now()->subWeek())->get();

        $this->assertCount(5, $recentDocuments);
    }

    public function test_document_date_casts_properly(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $date = now()->subDays(5);

        $document = Document::factory()->create([
            'uploaded_by' => $user->id,
            'document_date' => $date,
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $document->document_date);
    }
}
