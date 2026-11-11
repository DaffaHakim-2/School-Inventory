<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'user';
    }

    public function rules(): array
    {
        return [
            'loan_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'planned_return_date' => [
                'required',
                'date',
                'after:loan_date',
            ],

            'purpose' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.item_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('items', 'id')
                    ->whereNull('deleted_at'),
            ],

            'items.*.quantity_requested' => [
                'required',
                'integer',
                'min:1',
                'max:2147483647',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'loan_date.required' =>
                'Tanggal peminjaman wajib diisi.',

            'loan_date.after_or_equal' =>
                'Tanggal peminjaman tidak boleh sebelum hari ini.',

            'planned_return_date.required' =>
                'Tanggal rencana pengembalian wajib diisi.',

            'planned_return_date.after' =>
                'Tanggal pengembalian harus setelah tanggal peminjaman.',

            'items.required' =>
                'Pilih minimal satu barang.',

            'items.min' =>
                'Pengajuan harus memiliki minimal satu barang.',

            'items.*.item_id.required' =>
                'Barang wajib dipilih.',

            'items.*.item_id.distinct' =>
                'Barang yang sama tidak boleh dipilih lebih dari satu kali.',

            'items.*.item_id.exists' =>
                'Barang tidak ditemukan atau sudah dihapus.',

            'items.*.quantity_requested.required' =>
                'Jumlah barang wajib diisi.',

            'items.*.quantity_requested.integer' =>
                'Jumlah barang harus berupa angka bulat.',

            'items.*.quantity_requested.min' =>
                'Jumlah barang minimal 1.',

            'purpose.max' =>
                'Tujuan peminjaman maksimal 1000 karakter.',
        ];
    }
}