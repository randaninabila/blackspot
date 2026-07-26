<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isVerifikator() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'hasil_verifikasi'   => 'required|string|max:255',
            'catatan_verifikasi' => 'required|string|max:1000',
            'foto_bukti'         => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
        ];
    }
}
