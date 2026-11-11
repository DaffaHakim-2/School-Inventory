<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexActivityLogRequest extends FormRequest
{
    /**
     * Hanya Admin yang boleh mengakses halaman Activity Log.
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi pencarian dan filter.
     */
    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'action_type' => [
                'nullable',
                'string',
                Rule::in([
                    'loan_approved',
                    'loan_rejected',
                    'loan_issued',
                    'loan_returned',
                    'category_created',
                    'item_created',
                    'user_created',
                    'user_updated',
                    'user_password_reset',
                    'user_deleted',
                ]),
            ],
        ];
    }

    /**
     * Pesan validasi.
     */
    public function messages(): array
    {
        return [
            'search.string' => 'Pencarian harus berupa teks.',
            'search.max' => 'Pencarian maksimal 100 karakter.',
            'action_type.in' => 'Jenis aktivitas tidak valid.',
        ];
    }
}