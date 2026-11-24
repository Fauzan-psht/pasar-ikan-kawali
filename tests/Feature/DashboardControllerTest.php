<?php

namespace Tests\Feature;

use App\Models\ProfilPasar;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sidebar_uses_the_market_profile_logo_and_name(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
        $profil = ProfilPasar::current();
        $profil->update([
            'nama' => 'Pasar Contoh',
            'logo' => 'profil/logo-contoh.webp',
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Pasar Contoh')
            ->assertSee(asset('storage/profil/logo-contoh.webp'));
    }

    public function test_admin_sidebar_links_to_customer_and_activity_log_pages(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee(route('admin.pelanggan.index'))
            ->assertSee(route('admin.log.index'))
            ->assertSee('Pelanggan')
            ->assertSee('Log Aktivitas');
    }

    public function test_dashboard_layout_includes_loading_and_logout_confirmation_support(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('id="loading-overlay"', false)
            ->assertSee('onclick="confirmLogout()"', false)
            ->assertSee('function confirmLogout()', false)
            ->assertSee('sweetalert2@11', false);
    }

    public function test_admin_dashboard_shows_the_ten_most_recent_transactions(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        foreach (range(1, 12) as $number) {
            $transaction = Transaksi::create([
                'nomor' => 'TRX-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT),
                'user_id' => $admin->id,
                'kanal' => 'pos',
                'total' => 10000,
                'bayar' => 10000,
                'kembalian' => 0,
                'status' => 'paid',
            ]);
            $transaction->forceFill([
                'created_at' => now()->subMinutes($number),
            ])->save();
        }

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('TRX-0001')
            ->assertSee('TRX-0010')
            ->assertDontSee('TRX-0011');
    }
}
