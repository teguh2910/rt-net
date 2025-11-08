<?php

namespace Tests\Browser;

use App\Models\FinancialReport;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FinancialReportManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_financial_report_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        FinancialReport::factory()->count(3)->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/financial-reports')
                ->assertSee('Financial Reports')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_create_financial_report(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/financial-reports')
                ->clickLink('New')
                ->waitForText('Create Financial Report')
                ->type('data.month', '11')
                ->type('data.year', '2025')
                ->type('data.opening_balance', '5000000')
                ->type('data.total_income', '2000000')
                ->type('data.total_expense', '1500000')
                ->type('data.closing_balance', '5500000')
                ->type('data.notes', 'Laporan keuangan November 2025')
                ->press('Create')
                ->pause(1000)
                ->assertSee('2025');
        });
    }

    public function test_bendahara_can_create_financial_report(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($bendahara) {
            $browser->loginAs($bendahara)
                ->visit('/admin/financial-reports')
                ->assertSee('New')
                ->clickLink('New')
                ->waitForText('Create Financial Report')
                ->assertSee('Month');
        });
    }

    public function test_warga_can_view_financial_reports(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $admin = User::factory()->create(['role' => 'admin_rt']);
        FinancialReport::factory()->count(2)->create(['created_by' => $admin->id]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/financial-reports')
                ->assertSee('Financial Reports')
                ->assertPresent('table');
        });
    }

    public function test_admin_can_edit_financial_report(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $report = FinancialReport::factory()->create([
            'created_by' => $admin->id,
            'month' => 10,
            'year' => 2025,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/financial-reports')
                ->waitForText('2025')
                ->click('table tbody tr:first-child button[title="Edit"]')
                ->waitForText('Edit Financial Report')
                ->type('data.notes', 'Updated notes for financial report')
                ->press('Save changes')
                ->pause(1000)
                ->assertSee('2025');
        });
    }

    public function test_admin_can_delete_financial_report(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $report = FinancialReport::factory()->create([
            'created_by' => $admin->id,
            'month' => 12,
            'year' => 2024,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/financial-reports')
                ->waitForText('2024')
                ->click('tr:has-text("2024") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('2024');
        });
    }
}
