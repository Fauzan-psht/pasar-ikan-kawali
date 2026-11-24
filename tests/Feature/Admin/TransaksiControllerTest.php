<?php

namespace Tests\Feature\Admin;

use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_transaction_list_for_server_side_search(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.transaksi.index'));

        $response->assertOk()
            ->assertSee('transactionsTable')
            ->assertSee('voidModal');
    }

    public function test_admin_can_search_transactions_through_the_server_side_table(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
        Transaksi::create([
            'nomor' => 'TRX-TEST-1',
            'user_id' => $admin->id,
            'kanal' => 'pos',
            'total' => 25000,
            'bayar' => 25000,
            'kembalian' => 0,
            'status' => 'paid',
        ]);

        $columnNames = [
            'transaksis.nomor',
            'transaksis.created_at',
            'transaksis.kanal',
            'users.name',
            'pelanggans.nama',
            'transaksis.total',
            'transaksis.status',
            'actions',
            'transaksis.id',
        ];
        $columns = array_map(fn (string $name): array => ['name' => $name], $columnNames);
        $response = $this->actingAs($admin)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->getJson(route('admin.transaksi.index', [
                'columns' => $columns,
                'draw' => 1,
                'length' => 20,
                'order' => [['column' => 1, 'dir' => 'desc']],
                'search' => ['value' => 'TRX-TEST-1'],
                'start' => 0,
            ]));

        $response->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonPath('data.0.nomor', 'TRX-TEST-1')
            ->assertJsonPath('data.0.user_name', 'Admin')
            ->assertJsonPath('data.0.status', 'paid');
        $this->assertStringContainsString('data-bs-target="#voidModal"', $response->json('data.0.actions'));
    }
}
