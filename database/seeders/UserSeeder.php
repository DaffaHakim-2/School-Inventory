<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@inventaris.test',
                'password' => 'Admin12345',
                'role' => 'admin',
            ],
            [
                'name' => 'Pengguna Pertama',
                'email' => 'user1@inventaris.test',
                'password' => 'User12345',
                'role' => 'user',
            ],
            [
                'name' => 'Pengguna Kedua',
                'email' => 'user2@inventaris.test',
                'password' => 'User12345',
                'role' => 'user',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );

            // Role hanya ditetapkan melalui proses yang dikendalikan aplikasi.
            $user->forceFill([
                'role' => $data['role'],
            ])->save();
        }
    }
}