<?php

namespace Tests\Browser;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DocumentManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_document_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        Document::factory()->count(3)->create(['uploaded_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/documents')
                ->assertSee('Documents')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_create_document(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/documents')
                ->clickLink('New')
                ->waitForText('Create Document')
                ->type('data.title', 'SK Pengurus RT 2025')
                ->type('data.file_path', 'documents/sk-pengurus.pdf')
                ->select('data.file_type', 'pdf')
                ->type('data.description', 'Surat Keputusan Pengurus RT periode 2025')
                ->press('Create')
                ->waitForText('SK Pengurus RT 2025')
                ->assertSee('SK Pengurus RT 2025');
        });
    }

    public function test_ketua_rt_can_create_document(): void
    {
        $ketua = User::factory()->create([
            'role' => 'ketua_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua)
                ->visit('/admin/documents')
                ->assertSee('New')
                ->clickLink('New')
                ->waitForText('Create Document')
                ->assertSee('Title');
        });
    }

    public function test_warga_can_view_documents(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $admin = User::factory()->create(['role' => 'admin_rt']);
        Document::factory()->count(2)->create(['uploaded_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/documents')
                ->assertSee('Documents')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_edit_document(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $document = Document::factory()->create([
            'uploaded_by' => $admin->id,
            'title' => 'Original Document Title',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/documents')
                ->waitForText('Original Document Title')
                ->click('tr:has-text("Original Document Title") button[title="Edit"]')
                ->waitForText('Edit Document')
                ->type('data.title', 'Updated Document Title')
                ->press('Save changes')
                ->waitForText('Updated Document Title')
                ->assertSee('Updated Document Title');
        });
    }

    public function test_admin_can_delete_document(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $document = Document::factory()->create([
            'uploaded_by' => $admin->id,
            'title' => 'Document To Delete',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/documents')
                ->waitForText('Document To Delete')
                ->click('tr:has-text("Document To Delete") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('Document To Delete');
        });
    }
}
