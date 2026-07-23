<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationActionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status'         => 'nullable|in:approved,rejected,revisi,perlu_revisi',
            'catatan_revisi' => 'required_if:status,revisi,perlu_revisi|nullable|string|max:1000',
            'alasan_revisi'  => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_revisi.required_if' => 'Alasan / Catatan revisi wajib diisi jika data dikembalikan untuk revisi.',
        ];
    }
}
