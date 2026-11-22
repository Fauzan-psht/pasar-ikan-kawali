<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pasarkanikawali.id'],
            [
                'name' => 'Admin Pasar Ikan',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@pasarkanikawali.id'],
            [
                'name' => 'Kasir Pasar Ikan',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'is_active' => true,
            ]
        );
    }
}