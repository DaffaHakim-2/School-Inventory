<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
{
    /**
     * Memastikan hanya Admin yang dapat mencatat pengembalian.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Aturan validasi input pengembalian.
     */
    public function rules(): array
    {
        return [
            'return_date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

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

            'items.*.quantity_returned' => [
                'required',
                'integer',
                'min:1',
                'max:2147483647',
            ],

            'items.*.condition' => [
                'required',
                'in:good,damaged,lost',
            ],

            'items.*.notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'return_date.required' =>
            'Tanggal pengembalian wajib diisi.',

            'return_date.date' =>
            'Tanggal pengembalian tidak valid.',

            'notes.max' =>
            'Catatan pengembalian maksimal 1000 karakter.',

            'items.required' =>
            'Data barang yang dikembalikan wajib diisi.',

            'items.min' =>
            'Minimal harus ada satu barang yang dikembalikan.',

            'items.*.loan_detail_id.required' =>
            'Detail peminjaman wajib dipilih.',

            'items.*.loan_detail_id.distinct' =>
            'Detail barang tidak boleh dipilih lebih dari satu kali.',

            'items.*.loan_detail_id.exists' =>
            'Detail peminjaman tidak ditemukan.',

            'items.*.quantity_returned.required' =>
            'Jumlah barang yang dikembalikan wajib diisi.',

            'items.*.quantity_returned.integer' =>
            'Jumlah pengembalian harus berupa angka bulat.',

            'items.*.quantity_returned.min' =>
            'Jumlah barang yang dikembalikan minimal 1.',

            'items.*.condition.required' =>
            'Kondisi barang wajib dipilih.',

            'items.*.condition.in' =>
            'Kondisi barang harus good, damaged, atau lost.',

            'items.*.notes.max' =>
            'Catatan kondisi barang maksimal 1000 karakter.',
        ];
    }

    /**
     * Nama atribut untuk pesan validasi.
     */
    public function attributes(): array
    {
        return [
            'return_date' => 'tanggal pengembalian',
            'notes' => 'catatan pengembalian',
            'items' => 'rincian barang',
            'items.*.loan_detail_id' => 'barang yang dipinjam',
            'items.*.quantity_returned' => 'jumlah pengembalian',
            'items.*.condition' => 'kondisi barang',
            'items.*.notes' => 'catatan kondisi barang',
        ];
    }
}
