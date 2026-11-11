<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\ItemReturn;
use App\Models\ReturnDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReturnSeeder extends Seeder
{
    /**
     * Menjalankan seeder pengembalian.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@inventaris.test')
            ->firstOrFail();

        $returnData = [
            [
                'return_code' => 'PNG-2026-001',
                'loan_code' => 'PJM-2026-004',
                'return_date' => '2026-10-20',
                'notes' => 'Pengembalian tahap pertama.',
                'item_code' => 'ELK-001',
                'quantity_returned' => 2,
                'condition' => 'good',
            ],
            [
                'return_code' => 'PNG-2026-002',
                'loan_code' => 'PJM-2026-004',
                'return_date' => '2026-10-22',
                'notes' => 'Pengembalian tahap kedua. Seluruh laptop telah dikembalikan.',
                'item_code' => 'ELK-001',
                'quantity_returned' => 2,
                'condition' => 'good',
            ],
            [
                'return_code' => 'PNG-2026-003',
                'loan_code' => 'PJM-2026-005',
                'return_date' => '2026-10-24',
                'notes' => 'Seluruh mikroskop dikembalikan dalam kondisi baik.',
                'item_code' => 'PEL-003',
                'quantity_returned' => 2,
                'condition' => 'good',
            ],
        ];

        foreach ($returnData as $data) {
            $loan = Loan::where(
                'loan_code',
                $data['loan_code']
            )->firstOrFail();

            $loanDetail = LoanDetail::where('loan_id', $loan->id)
                ->whereHas('item', function ($query) use ($data) {
                    $query->where('code', $data['item_code']);
                })
                ->firstOrFail();

            $itemReturn = ItemReturn::updateOrCreate(
                [
                    'return_code' => $data['return_code'],
                ],
                [
                    'loan_id' => $loan->id,
                    'received_by' => $admin->id,
                    'return_date' => $data['return_date'],
                    'notes' => $data['notes'],
                ]
            );

            ReturnDetail::updateOrCreate(
                [
                    'return_id' => $itemReturn->id,
                    'loan_detail_id' => $loanDetail->id,
                ],
                [
                    'quantity_returned' => $data['quantity_returned'],
                    'condition' => $data['condition'],
                    'notes' => null,
                ]
            );
        }
    }
}