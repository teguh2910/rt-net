<?php

namespace Tests\Feature;

use App\Models\Finance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_finance_income(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $financeData = [
            'type' => 'pemasukan',
            'category' => 'iuran',
            'description' => 'Iuran bulanan November',
            'amount' => 50000,
            'transaction_date' => now()->format('Y-m-d'),
        ];

        $finance = Finance::create(array_merge($financeData, ['user_id' => $admin->id]));

        $this->assertDatabaseHas('finances', [
            'type' => 'pemasukan',
            'category' => 'iuran',
            'description' => 'Iuran bulanan November',
            'amount' => 50000,
            'user_id' => $admin->id,
        ]);

        $this->assertEquals('pemasukan', $finance->type);
        $this->assertEquals(50000.00, (float) $finance->amount);
    }

    public function test_admin_can_create_finance_expense(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);

        $this->actingAs($admin);

        $financeData = [
            'type' => 'pengeluaran',
            'category' => 'kebersihan',
            'description' => 'Biaya kebersihan jalan',
            'amount' => 100000,
            'transaction_date' => now()->format('Y-m-d'),
            'user_id' => $admin->id,
        ];

        $finance = Finance::create($financeData);

        $this->assertDatabaseHas('finances', [
            'type' => 'pengeluaran',
            'category' => 'kebersihan',
            'amount' => 100000,
        ]);

        $this->assertEquals('pengeluaran', $finance->type);
    }

    public function test_bendahara_can_create_finance(): void
    {
        $bendahara = User::factory()->create(['role' => 'bendahara', 'is_active' => true]);

        $this->actingAs($bendahara);

        $finance = Finance::factory()->create([
            'user_id' => $bendahara->id,
            'type' => 'pemasukan',
            'category' => 'donasi',
        ]);

        $this->assertDatabaseHas('finances', [
            'id' => $finance->id,
            'user_id' => $bendahara->id,
        ]);
    }

    public function test_finance_has_user(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $finance = Finance::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $finance->user);
        $this->assertEquals($user->id, $finance->user->id);
    }

    public function test_finance_can_be_updated(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $finance = Finance::factory()->create(['user_id' => $admin->id]);

        $this->actingAs($admin);

        $finance->update([
            'amount' => 75000,
            'description' => 'Updated description',
        ]);

        $this->assertDatabaseHas('finances', [
            'id' => $finance->id,
            'amount' => 75000,
            'description' => 'Updated description',
        ]);
    }

    public function test_finance_can_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin_rt', 'is_active' => true]);
        $finance = Finance::factory()->create(['user_id' => $admin->id]);

        $this->actingAs($admin);

        $finance->delete();

        $this->assertDatabaseMissing('finances', [
            'id' => $finance->id,
        ]);
    }

    public function test_finance_amount_casts_to_decimal(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $finance = Finance::factory()->create([
            'user_id' => $user->id,
            'amount' => 100000.50,
        ]);

        $this->assertEquals('100000.50', $finance->amount);
    }

    public function test_finance_transaction_date_casts_to_date(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);
        $date = now()->subDays(5);

        $finance = Finance::factory()->create([
            'user_id' => $user->id,
            'transaction_date' => $date,
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $finance->transaction_date);
        $this->assertEquals($date->format('Y-m-d'), $finance->transaction_date->format('Y-m-d'));
    }

    public function test_can_filter_finances_by_type(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Finance::factory()->count(3)->create([
            'user_id' => $user->id,
            'type' => 'pemasukan',
        ]);

        Finance::factory()->count(2)->create([
            'user_id' => $user->id,
            'type' => 'pengeluaran',
        ]);

        $incomes = Finance::where('type', 'pemasukan')->get();
        $expenses = Finance::where('type', 'pengeluaran')->get();

        $this->assertCount(3, $incomes);
        $this->assertCount(2, $expenses);
    }

    public function test_can_filter_finances_by_category(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Finance::factory()->count(2)->create([
            'user_id' => $user->id,
            'category' => 'iuran',
        ]);

        Finance::factory()->count(3)->create([
            'user_id' => $user->id,
            'category' => 'donasi',
        ]);

        $iuran = Finance::where('category', 'iuran')->get();
        $donasi = Finance::where('category', 'donasi')->get();

        $this->assertCount(2, $iuran);
        $this->assertCount(3, $donasi);
    }

    public function test_can_calculate_total_income(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Finance::factory()->create([
            'user_id' => $user->id,
            'type' => 'pemasukan',
            'amount' => 50000,
        ]);

        Finance::factory()->create([
            'user_id' => $user->id,
            'type' => 'pemasukan',
            'amount' => 75000,
        ]);

        Finance::factory()->create([
            'user_id' => $user->id,
            'type' => 'pengeluaran',
            'amount' => 30000,
        ]);

        $totalIncome = Finance::where('type', 'pemasukan')->sum('amount');

        $this->assertEquals(125000, $totalIncome);
    }

    public function test_can_calculate_total_expense(): void
    {
        $user = User::factory()->create(['role' => 'admin_rt']);

        Finance::factory()->create([
            'user_id' => $user->id,
            'type' => 'pengeluaran',
            'amount' => 40000,
        ]);

        Finance::factory()->create([
            'user_id' => $user->id,
            'type' => 'pengeluaran',
            'amount' => 60000,
        ]);

        Finance::factory()->create([
            'user_id' => $user->id,
            'type' => 'pemasukan',
            'amount' => 50000,
        ]);

        $totalExpense = Finance::where('type', 'pengeluaran')->sum('amount');

        $this->assertEquals(100000, $totalExpense);
    }
}
