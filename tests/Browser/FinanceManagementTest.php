<?php

namespace Tests\Browser;

use App\Models\Finance;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FinanceManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_can_view_finance_list(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        Finance::factory()->count(3)->create(['user_id' => $admin->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/finances')
                ->assertSee('Transaksi Keuangan')
                ->assertSee('Pemasukan')
                ->assertSee('Pengeluaran');
        });
    }

    public function test_admin_can_create_finance_income(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/finances')
                ->clickLink('New')
                ->waitForText('Create Finance')
                ->select('data.type', 'pemasukan')
                ->select('data.category', 'iuran')
                ->type('data.description', 'Iuran Bulanan Test')
                ->type('data.amount', '50000')
                ->press('Create')
                ->waitForText('Iuran Bulanan Test')
                ->assertSee('50,000.00');
        });
    }

    public function test_admin_can_create_finance_expense(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/finances')
                ->clickLink('New')
                ->waitForText('Create Finance')
                ->select('data.type', 'pengeluaran')
                ->select('data.category', 'kebersihan')
                ->type('data.description', 'Biaya Kebersihan Test')
                ->type('data.amount', '100000')
                ->press('Create')
                ->waitForText('Biaya Kebersihan Test')
                ->assertSee('100,000.00');
        });
    }

    public function test_bendahara_can_create_finance(): void
    {
        $bendahara = User::factory()->create([
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($bendahara) {
            $browser->loginAs($bendahara)
                ->visit('/admin/finances')
                ->clickLink('New')
                ->waitForText('Create Finance')
                ->assertSee('Jenis Transaksi');
        });
    }

    public function test_warga_cannot_create_finance(): void
    {
        $warga = User::factory()->create([
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($warga) {
            $browser->loginAs($warga)
                ->visit('/admin/finances')
                ->assertDontSee('New');
        });
    }

    public function test_admin_can_edit_finance(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $finance = Finance::factory()->create([
            'user_id' => $admin->id,
            'description' => 'Original Description',
            'amount' => 50000,
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/finances')
                ->waitForText('Original Description')
                ->click('tr:has-text("Original Description") button[title="Edit"]')
                ->waitForText('Edit Finance')
                ->type('data.description', 'Updated Description')
                ->type('data.amount', '75000')
                ->press('Save changes')
                ->waitForText('Updated Description')
                ->assertSee('75,000.00');
        });
    }

    public function test_admin_can_delete_finance(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_rt',
            'is_active' => true,
        ]);

        $finance = Finance::factory()->create([
            'user_id' => $admin->id,
            'description' => 'To Be Deleted',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/finances')
                ->waitForText('To Be Deleted')
                ->click('tr:has-text("To Be Deleted") button[title="Delete"]')
                ->waitForText('Are you sure')
                ->press('Confirm')
                ->pause(1000)
                ->assertDontSee('To Be Deleted');
        });
    }
}
