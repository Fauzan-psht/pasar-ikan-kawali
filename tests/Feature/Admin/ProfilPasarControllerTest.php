<?php

namespace Tests\Feature\Admin;

use App\Models\ProfilPasar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilPasarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_accepts_thirteen_digit_phone_and_whatsapp_values(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->put(route('admin.profil.update'), [
            'nama' => 'Pasar Ikan Kawali',
            'telepon' => '0265123456789',
            'whatsapp' => '0812345678901',
        ]);

        $response->assertRedirect(route('admin.profil.index'));
        $this->assertDatabaseHas('profil_pasar', [
            'telepon' => '0265123456789',
            'whatsapp' => '0812345678901',
        ]);
    }

    public function test_profile_rejects_non_digit_phone_and_whatsapp_values(): void
    {
        $admin = $this->createAdmin();
        $profil = ProfilPasar::current();
        $profil->update([
            'telepon' => '0265123456',
            'whatsapp' => '08123456789',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.profil.index'))
            ->put(route('admin.profil.update'), [
                'nama' => $profil->nama,
                'telepon' => '0265-123456',
                'whatsapp' => '08123abc',
            ]);

        $response->assertRedirect(route('admin.profil.index'))
            ->assertSessionHasErrors([
                'telepon' => 'Nomor telepon hanya boleh berisi angka dan maksimal 13 digit.',
                'whatsapp' => 'Nomor WhatsApp hanya boleh berisi angka dan maksimal 13 digit.',
            ]);
        $this->assertDatabaseHas('profil_pasar', [
            'id' => $profil->id,
            'telepon' => '0265123456',
            'whatsapp' => '08123456789',
        ]);
    }

    public function test_profile_rejects_phone_and_whatsapp_numbers_longer_than_thirteen_digits(): void
    {
        $admin = $this->createAdmin();
        $profil = ProfilPasar::current();

        $response = $this->actingAs($admin)
            ->from(route('admin.profil.index'))
            ->put(route('admin.profil.update'), [
                'nama' => $profil->nama,
                'telepon' => '02651234567890',
                'whatsapp' => '08123456789012',
            ]);

        $response->assertRedirect(route('admin.profil.index'))
            ->assertSessionHasErrors([
                'telepon' => 'Nomor telepon hanya boleh berisi angka dan maksimal 13 digit.',
                'whatsapp' => 'Nomor WhatsApp hanya boleh berisi angka dan maksimal 13 digit.',
            ]);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
