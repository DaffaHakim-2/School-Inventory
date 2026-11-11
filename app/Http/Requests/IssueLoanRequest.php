<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssueLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.loan_detail_id' => [
                'required',
                'integer',
                'distinct',
                'exists:loan_details,id',
            ],

            'items.*.quantity_issued' => [
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
            'items.required' =>
                'Data penyerahan barang wajib diisi.',

            'items.min' =>
                'Minimal harus ada satu detail barang.',

            'items.*.loan_detail_id.required' =>
                'Detail peminjaman wajib dipilih.',

            'items.*.loan_detail_id.distinct' =>
                'Detail barang tidak boleh dipilih lebih dari satu kali.',

            'items.*.loan_detail_id.exists' =>
                'Detail peminjaman tidak ditemukan.',

            'items.*.quantity_issued.required' =>
                'Jumlah barang yang diserahkan wajib diisi.',

            'items.*.quantity_issued.integer' =>
                'Jumlah barang yang diserahkan harus berupa angka bulat.',

            'items.*.quantity_issued.min' =>
                'Jumlah barang yang diserahkan tidak boleh negatif.',
        ];
    }
}