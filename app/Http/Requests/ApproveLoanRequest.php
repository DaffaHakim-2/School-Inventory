<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],

            'items.*.loan_detail_id' => [
                'required',
                'integer',
                'distinct',
                'exists:loan_details,id',
            ],

            'items.*.quantity_approved' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Rincian barang wajib diisi.',
            'items.min' => 'Minimal harus ada satu rincian barang.',
            'items.*.loan_detail_id.required' => 'Rincian barang tidak valid.',
            'items.*.loan_detail_id.distinct' => 'Rincian barang tidak boleh duplikat.',
            'items.*.quantity_approved.required' => 'Jumlah yang disetujui wajib diisi.',
            'items.*.quantity_approved.min' => 'Jumlah yang disetujui tidak boleh negatif.',
        ];
    }
}