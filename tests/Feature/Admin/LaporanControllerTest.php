<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class LaporanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_excel_report_for_selected_dates(): void
    {
        Excel::fake();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $fileName = 'Laporan_Penjualan_2026-11-01_sd_2026-11-23.xlsx';

        $response = $this->actingAs($admin)->get(route('admin.laporan.excel', [
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-23',
        ]));

        $response->assertOk();
        Excel::assertDownloaded($fileName);
    }
}
