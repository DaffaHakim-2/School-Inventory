<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanDetail;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Menjalankan seeder peminjaman.
     */
    public function run(): void
    {
        // Mengambil akun pengguna
        $user1 = User::where('email', 'user1@inventaris.test')
            ->firstOrFail();

        $user2 = User::where('email', 'user2@inventaris.test')
            ->firstOrFail();

        // Mengambil akun admin
        $admin = User::where('email', 'admin@inventaris.test')
            ->firstOrFail();

        // Mengambil barang berdasarkan kode
        $laptop = Item::where('code', 'ELK-001')->firstOrFail();
        $proyektor = Item::where('code', 'ELK-002')->firstOrFail();
        $kursi = Item::where('code', 'FUR-002')->firstOrFail();
        $mikroskop = Item::where('code', 'PEL-003')->firstOrFail();

        $loans = [
            [
                'loan_code' => 'PJM-2026-001',
                'user_id' => $user1->id,
                'approved_by' => null,
                'loan_date' => '2026-10-20',
                'planned_return_date' => '2026-10-27',
                'purpose' => 'Pengajuan peminjaman laptop untuk kegiatan pembelajaran.',
                'status' => 'pending',
                'approved_at' => null,
                'item' => $laptop,
                'requested' => 2,
                'approved' => 0,
                'issued' => 0,
            ],
            [
                'loan_code' => 'PJM-2026-002',
                'user_id' => $user2->id,
                'approved_by' => $admin->id,
                'loan_date' => '2026-10-21',
                'planned_return_date' => '2026-10-28',
                'purpose' => 'Pengajuan peminjaman proyektor untuk kegiatan sekolah.',
                'status' => 'rejected',
                'approved_at' => null,
                'item' => $proyektor,
                'requested' => 1,
                'approved' => 0,
                'issued' => 0,
            ],
            [
                'loan_code' => 'PJM-2026-003',
                'user_id' => $user1->id,
                'approved_by' => $admin->id,
                'loan_date' => '2026-10-22',
                'planned_return_date' => '2026-11-10',
                'purpose' => 'Peminjaman kursi untuk kegiatan rapat sekolah.',
                'status' => 'borrowed',
                'approved_at' => '2026-10-22 09:00:00',
                'item' => $kursi,
                'requested' => 5,
                'approved' => 5,
                'issued' => 5,
            ],
            [
                'loan_code' => 'PJM-2026-004',
                'user_id' => $user2->id,
                'approved_by' => $admin->id,
                'loan_date' => '2026-10-15',
                'planned_return_date' => '2026-10-25',
                'purpose' => 'Peminjaman laptop untuk kegiatan administrasi sekolah.',
                'status' => 'returned',
                'approved_at' => '2026-10-15 09:00:00',
                'item' => $laptop,
                'requested' => 4,
                'approved' => 4,
                'issued' => 4,
            ],
            [
                'loan_code' => 'PJM-2026-005',
                'user_id' => $user1->id,
                'approved_by' => $admin->id,
                'loan_date' => '2026-10-16',
                'planned_return_date' => '2026-10-26',
                'purpose' => 'Peminjaman mikroskop untuk praktikum IPA.',
                'status' => 'returned',
                'approved_at' => '2026-10-16 10:00:00',
                'item' => $mikroskop,
                'requested' => 2,
                'approved' => 2,
                'issued' => 2,
            ],
        ];

        foreach ($loans as $loanData) {

            // Membuat atau memperbarui data peminjaman
            $loan = Loan::updateOrCreate(
                ['loan_code' => $loanData['loan_code']],
                [
                    'user_id' => $loanData['user_id'],
                    'approved_by' => $loanData['approved_by'],
                    'loan_date' => $loanData['loan_date'],
                    'planned_return_date' => $loanData['planned_return_date'],
                    'purpose' => $loanData['purpose'],
                    'status' => $loanData['status'],
                    'approved_at' => $loanData['approved_at'],
                ]
            );

            // Membuat atau memperbarui detail peminjaman
            LoanDetail::updateOrCreate(
                [
                    'loan_id' => $loan->id,
                    'item_id' => $loanData['item']->id,
                ],
                [
                    'quantity_requested' => $loanData['requested'],
                    'quantity_approved' => $loanData['approved'],
                    'quantity_issued' => $loanData['issued'],
                ]
            );
        }
    }
}