<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsulanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow public bottom-up submission
    }

    public function rules(): array
    {
        return [
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id'      => 'nullable|exists:desa,id',
            'nama_lokasi'  => 'required|string|max:255',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
            'radius'       => 'nullable|numeric|min:0',
            'keterangan'   => 'nullable|string|max:1000',
            'foto'         => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
