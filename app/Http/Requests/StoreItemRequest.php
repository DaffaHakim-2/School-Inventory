<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->whereNull('deleted_at'),
            ],

            'code' => [
                'required',
                'string',
                'max:255',
                'unique:items,code',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'initial_stock' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',//batasan stok maksimum untuk tipe unsigned integer
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',

            'code.required' => 'Kode barang wajib diisi.',
            'code.unique' => 'Kode barang sudah digunakan.',

            'name.required' => 'Nama barang wajib diisi.',

            'initial_stock.required' => 'Stok awal wajib diisi.',
            'initial_stock.integer' => 'Stok harus berupa angka bulat.',
            'initial_stock.min' => 'Stok tidak boleh negatif.',
        ];
    }
}