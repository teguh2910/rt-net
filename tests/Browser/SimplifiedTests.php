<?php

namespace Tests\Browser;

use App\Models\Announcement;
use App\Models\DigitalLetter;
use App\Models\Document;
use App\Models\Event;
use App\Models\Finance;
use App\Models\FinancialReport;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SimplifiedTests extends DuskTestCase
{
    use DatabaseMigrations;

    // ============== FINANCE TESTS ==============

    public function test_admin_can_view_finances(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        Finance::factory()->count(3)->create(['user_id' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/finances')
                ->pause(500)
                ->assertPathIs('/admin/finances');
        });
    }

    public function test_admin_can_access_create_finance_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/finances/create')
                ->pause(500)
                ->assertPathIs('/admin/finances/create');
        });
    }

    public function test_admin_can_access_edit_finance_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $finance = Finance::factory()->create(['user_id' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin, $finance) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/finances/{$finance->id}/edit")
                ->pause(500)
                ->assertPathIs("/admin/finances/{$finance->id}/edit");
        });
    }

    public function test_bendahara_can_access_finances(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($bendahara) {
            $browser->loginAs($bendahara, 'web')
                ->visit('/admin/finances')
                ->pause(500)
                ->assertPathIs('/admin/finances');
        });
    }

    // ============== RESIDENT TESTS ==============

    public function test_admin_can_view_residents(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        Resident::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/residents')
                ->pause(500)
                ->assertPathIs('/admin/residents');
        });
    }

    public function test_admin_can_access_create_resident_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/residents/create')
                ->pause(500)
                ->assertPathIs('/admin/residents/create');
        });
    }

    public function test_admin_can_access_edit_resident_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $resident = Resident::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $resident) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/residents/{$resident->id}/edit")
                ->pause(500)
                ->assertPathIs("/admin/residents/{$resident->id}/edit");
        });
    }

    public function test_warga_can_view_residents(): void
    {
        $warga = User::factory()->create(['role' => 'warga', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga, 'web')
                ->visit('/admin/residents')
                ->pause(500)
                ->assertPathIs('/admin/residents');
        });
    }

    // ============== ANNOUNCEMENT TESTS ==============

    public function test_admin_can_view_announcements(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        Announcement::factory()->count(3)->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/announcements')
                ->pause(500)
                ->assertPathIs('/admin/announcements');
        });
    }

    public function test_admin_can_access_create_announcement_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/announcements/create')
                ->pause(500)
                ->assertPathIs('/admin/announcements/create');
        });
    }

    public function test_admin_can_access_edit_announcement_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $announcement = Announcement::factory()->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin, $announcement) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/announcements/{$announcement->id}/edit")
                ->pause(500)
                ->assertPathIs("/admin/announcements/{$announcement->id}/edit");
        });
    }

    public function test_ketua_rt_can_access_announcements(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua, 'web')
                ->visit('/admin/announcements')
                ->pause(500)
                ->assertPathIs('/admin/announcements');
        });
    }

    // ============== EVENT TESTS ==============

    public function test_admin_can_view_events(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        Event::factory()->count(3)->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/events')
                ->pause(500)
                ->assertPathIs('/admin/events');
        });
    }

    public function test_admin_can_access_create_event_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/events/create')
                ->pause(500)
                ->assertPathIs('/admin/events/create');
        });
    }

    public function test_admin_can_access_edit_event_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $event = Event::factory()->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin, $event) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/events/{$event->id}/edit")
                ->pause(500)
                ->assertPathIs("/admin/events/{$event->id}/edit");
        });
    }

    public function test_ketua_rt_can_access_events(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua, 'web')
                ->visit('/admin/events')
                ->pause(500)
                ->assertPathIs('/admin/events');
        });
    }

    // ============== DOCUMENT TESTS ==============

    public function test_admin_can_view_documents(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        Document::factory()->count(3)->create(['uploaded_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/documents')
                ->pause(500)
                ->assertPathIs('/admin/documents');
        });
    }

    public function test_admin_can_access_create_document_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/documents/create')
                ->pause(500)
                ->assertPathIs('/admin/documents/create');
        });
    }

    public function test_warga_can_view_documents(): void
    {
        $warga = User::factory()->create(['role' => 'warga', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga, 'web')
                ->visit('/admin/documents')
                ->pause(500)
                ->assertPathIs('/admin/documents');
        });
    }

    // ============== DIGITAL LETTER TESTS ==============

    public function test_admin_can_view_digital_letters(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        DigitalLetter::factory()->count(3)->create(['issued_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/digital-letters')
                ->pause(500)
                ->assertPathIs('/admin/digital-letters');
        });
    }

    public function test_admin_can_access_create_digital_letter_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/digital-letters/create')
                ->pause(500)
                ->assertPathIs('/admin/digital-letters/create');
        });
    }

    public function test_admin_can_access_edit_digital_letter_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $letter = DigitalLetter::factory()->create(['issued_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin, $letter) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/digital-letters/{$letter->id}/edit")
                ->pause(500)
                ->assertPathIs("/admin/digital-letters/{$letter->id}/edit");
        });
    }

    public function test_ketua_rt_can_access_digital_letters(): void
    {
        $ketua = User::factory()->create(['role' => 'ketua_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($ketua) {
            $browser->loginAs($ketua, 'web')
                ->visit('/admin/digital-letters')
                ->pause(500)
                ->assertPathIs('/admin/digital-letters');
        });
    }

    // ============== FINANCIAL REPORT TESTS ==============

    public function test_admin_can_view_financial_reports(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        FinancialReport::factory()->count(3)->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/financial-reports')
                ->pause(500)
                ->assertPathIs('/admin/financial-reports');
        });
    }

    public function test_admin_can_access_create_financial_report_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/financial-reports/create')
                ->pause(500)
                ->assertPathIs('/admin/financial-reports/create');
        });
    }

    public function test_admin_can_access_view_financial_report_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $report = FinancialReport::factory()->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin, $report) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/financial-reports/{$report->id}")
                ->pause(500)
                ->assertPathIs("/admin/financial-reports/{$report->id}");
        });
    }

    public function test_bendahara_can_access_financial_reports(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($bendahara) {
            $browser->loginAs($bendahara, 'web')
                ->visit('/admin/financial-reports')
                ->pause(500)
                ->assertPathIs('/admin/financial-reports');
        });
    }

    // ============== USER MANAGEMENT TESTS ==============

    public function test_admin_can_view_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/users')
                ->pause(500)
                ->assertPathIs('/admin/users');
        });
    }

    public function test_admin_can_access_create_user_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'web')
                ->visit('/admin/users/create')
                ->pause(500)
                ->assertPathIs('/admin/users/create');
        });
    }

    public function test_admin_can_access_edit_user_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'warga']);

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin, 'web')
                ->visit("/admin/users/{$user->id}/edit")
                ->pause(500)
                ->assertPathIs("/admin/users/{$user->id}/edit");
        });
    }

    public function test_warga_cannot_access_users_page(): void
    {
        $warga = User::factory()->create(['role' => 'warga', 'is_active' => true]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga, 'web')
                ->visit('/admin/users')
                ->pause(500)
                // Should be redirected or see error
                ->assertDontSee('Create');
        });
    }
}
