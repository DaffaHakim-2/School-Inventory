<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustItemStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'movement_type' => [
                'required',
                Rule::in(['stock_in', 'stock_adjustment']),
            ],

            'direction' => [
                'required',
                Rule::in(['increase', 'decrease']),
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:2147483647',
            ],

            'description' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'movement_type.required' => 'Jenis pergerakan wajib dipilih.',
            'movement_type.in' => 'Jenis pergerakan tidak valid.',

            'direction.required' => 'Arah perubahan stok wajib dipilih.',
            'direction.in' => 'Arah perubahan stok tidak valid.',

            'quantity.required' => 'Jumlah perubahan stok wajib diisi.',
            'quantity.integer' => 'Jumlah stok harus berupa angka bulat.',
            'quantity.min' => 'Jumlah perubahan minimal 1.',

            'description.required' => 'Alasan penyesuaian stok wajib diisi.',
            'description.max' => 'Alasan maksimal 1000 karakter.',
        ];
    }
}