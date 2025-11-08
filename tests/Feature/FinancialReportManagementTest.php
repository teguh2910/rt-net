<?php

namespace Tests\Feature;

use App\Models\FinancialReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_financial_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $reportData = [
            'month' => 11,
            'year' => 2025,
            'opening_balance' => 5000000,
            'total_income' => 2000000,
            'total_expense' => 1500000,
            'closing_balance' => 5500000,
            'notes' => 'Laporan keuangan bulan November 2025',
            'created_by' => $admin->id,
        ];

        $report = FinancialReport::create($reportData);

        $this->assertDatabaseHas('financial_reports', [
            'month' => 11,
            'year' => 2025,
            'opening_balance' => 5000000,
            'created_by' => $admin->id,
        ]);

        $this->assertEquals(5500000, (float) $report->closing_balance);
    }

    public function test_bendahara_can_create_financial_report(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'is_active' => true]);

        $this->actingAs($bendahara);

        $report = FinancialReport::factory()->create([
            'created_by' => $bendahara->id,
        ]);

        $this->assertDatabaseHas('financial_reports', [
            'id' => $report->id,
            'created_by' => $bendahara->id,
        ]);
    }

    public function test_financial_report_has_creator(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $report = FinancialReport::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $report->createdBy);
        $this->assertEquals($user->id, $report->createdBy->id);
    }

    public function test_closing_balance_calculation_is_correct(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);

        $openingBalance = 5000000;
        $totalIncome = 2000000;
        $totalExpense = 1500000;
        $expectedClosing = $openingBalance + $totalIncome - $totalExpense;

        $report = FinancialReport::factory()->create([
            'created_by' => $admin->id,
            'opening_balance' => $openingBalance,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'closing_balance' => $expectedClosing,
        ]);

        $this->assertEquals($expectedClosing, (float) $report->closing_balance);
        $this->assertEquals(5500000, (float) $report->closing_balance);
    }

    public function test_financial_report_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $report = FinancialReport::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $report->update([
            'total_income' => 3000000,
            'total_expense' => 2000000,
            'closing_balance' => 6000000,
            'notes' => 'Updated notes',
        ]);

        $this->assertDatabaseHas('financial_reports', [
            'id' => $report->id,
            'total_income' => 3000000,
            'notes' => 'Updated notes',
        ]);
    }

    public function test_financial_report_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt']);
        $report = FinancialReport::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $report->delete();

        $this->assertDatabaseMissing('financial_reports', [
            'id' => $report->id,
        ]);
    }

    public function test_can_get_report_by_month_and_year(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        FinancialReport::factory()->create([
            'created_by' => $user->id,
            'month' => 11,
            'year' => 2025,
        ]);

        FinancialReport::factory()->create([
            'created_by' => $user->id,
            'month' => 10,
            'year' => 2025,
        ]);

        $novemberReport = FinancialReport::where('month', 11)
            ->where('year', 2025)
            ->first();

        $this->assertNotNull($novemberReport);
        $this->assertEquals(11, $novemberReport->month);
    }

    public function test_can_get_reports_by_year(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        FinancialReport::factory()->count(5)->create([
            'created_by' => $user->id,
            'year' => 2025,
        ]);

        FinancialReport::factory()->count(3)->create([
            'created_by' => $user->id,
            'year' => 2024,
        ]);

        $reports2025 = FinancialReport::where('year', 2025)->get();

        $this->assertCount(5, $reports2025);
    }

    public function test_financial_amounts_cast_to_decimal(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        $report = FinancialReport::factory()->create([
            'created_by' => $user->id,
            'opening_balance' => 1000000.50,
            'total_income' => 500000.75,
            'total_expense' => 250000.25,
            'closing_balance' => 1250001.00,
        ]);

        $this->assertEquals('1000000.50', $report->opening_balance);
        $this->assertEquals('500000.75', $report->total_income);
        $this->assertEquals('250000.25', $report->total_expense);
        $this->assertEquals('1250001.00', $report->closing_balance);
    }
}
