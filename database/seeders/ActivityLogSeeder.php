<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@inventaris.test')
            ->firstOrFail();

        $user1 = User::where('email', 'user1@inventaris.test')
            ->firstOrFail();

        $user2 = User::where('email', 'user2@inventaris.test')
            ->firstOrFail();

        $activities = [
            [
                'user_id' => $admin->id,
                'action_type' => 'create',
                'description' => 'Admin menambahkan kategori Elektronik.',
                'created_at' => '2026-10-01 08:10:00',
            ],
            [
                'user_id' => $admin->id,
                'action_type' => 'create',
                'description' => 'Admin menambahkan 10 data barang inventaris.',
                'created_at' => '2026-10-01 09:00:00',
            ],
            [
                'user_id' => $user1->id,
                'action_type' => 'create',
                'description' => 'Pengguna Pertama mengajukan peminjaman PJM-2026-001.',
                'created_at' => '2026-10-20 08:30:00',
            ],
            [
                'user_id' => $user2->id,
                'action_type' => 'create',
                'description' => 'Pengguna Kedua mengajukan peminjaman PJM-2026-002.',
                'created_at' => '2026-10-21 08:45:00',
            ],
            [
                'user_id' => $admin->id,
                'action_type' => 'approve',
                'description' => 'Admin menyetujui peminjaman PJM-2026-003.',
                'created_at' => '2026-10-22 09:00:00',
            ],
            [
                'user_id' => $admin->id,
                'action_type' => 'reject',
                'description' => 'Admin menolak peminjaman PJM-2026-002.',
                'created_at' => '2026-10-22 10:00:00',
            ],
            [
                'user_id' => $admin->id,
                'action_type' => 'return',
                'description' => 'Admin menerima pengembalian tahap pertama PJM-2026-004.',
                'created_at' => '2026-10-20 14:00:00',
            ],
            [
                'user_id' => $admin->id,
                'action_type' => 'return',
                'description' => 'Admin menerima pengembalian seluruh mikroskop pada PJM-2026-005.',
                'created_at' => '2026-10-24 14:00:00',
            ],
        ];

        foreach ($activities as $activity) {
            ActivityLog::updateOrCreate(
                [
                    'user_id' => $activity['user_id'],
                    'action_type' => $activity['action_type'],
                    'description' => $activity['description'],
                    'created_at' => $activity['created_at'],
                ],
                $activity
            );
        }
    }
}