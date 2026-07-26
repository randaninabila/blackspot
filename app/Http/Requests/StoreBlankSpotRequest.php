<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\BlankSpot;

class StoreBlankSpotRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOperator()) {
            $inputKab = $this->input('kabupaten_id') ?? $this->route('kabupaten_id');
            if ($inputKab !== null && (int) $inputKab !== (int) $user->kabupaten_id) {
                return false; // Triggers 403 Forbidden!
            }
            return true;
        }

        return false;
    }

    protected function prepareForValidation(): void
    {
        $user = Auth::user();

        // Extract prioritas integer from keterangan if keterangan holds "Priority X" or "Prioritas X"
        if (!$this->filled('prioritas') && $this->filled('keterangan')) {
            if (preg_match('/(?:Priority|Prioritas)\s*(\d+)/i', $this->keterangan, $matches)) {
                $this->merge([
                    'prioritas' => (int) $matches[1],
                ]);
            } elseif (is_numeric($this->keterangan)) {
                $this->merge([
                    'prioritas' => (int) $this->keterangan,
                ]);
            }
        }

        // Paksa kabupaten_id milik Operator sendiri
        if ($user && $user->isOperator()) {
            $this->merge([
                'kabupaten_id' => $user->kabupaten_id,
            ]);
        }
    }

    public function rules(): array
    {
        $user = Auth::user();
        $kabupatenId = $user->isOperator() ? $user->kabupaten_id : $this->input('kabupaten_id');

        return [
            'kabupaten_id'    => $user->isOperator() ? 'nullable' : 'required|exists:kabupaten,id',
            'kecamatan_id'    => 'required|exists:kecamatan,id',
            'desa_id'         => 'nullable',
            'nama_desa'       => 'nullable|string|max:255',
            'desa'            => 'nullable|string|max:255',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'radius'          => 'nullable|numeric|min:0',
            'prioritas'       => [
                'required',
                'integer',
                'between:1,10',
                function ($attribute, $value, $fail) use ($kabupatenId) {
                    if ($value && $kabupatenId) {
                        $exists = BlankSpot::where('kabupaten_id', $kabupatenId)
                            ->where('prioritas', $value)
                            ->exists();
                        if ($exists) {
                            $fail("Prioritas P{$value} sudah digunakan pada Kabupaten/Kota ini. Setiap Kabupaten/Kota hanya diperbolehkan memiliki 1 data per tingkat prioritas (P1–P10).");
                        }
                    }
                },
            ],
            'tahun'           => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'semester'        => 'nullable|integer|between:1,2',
            'foto'            => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120',
            'nama_lokasi'     => 'nullable|string|max:255',
            'status_jaringan' => 'nullable|string|max:255',
            'keterangan'      => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'kabupaten_id.required' => 'Kabupaten/Kota wajib dipilih.',
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'nama_desa.required_without' => 'Nama Desa wajib diisi.',
            'latitude.required' => 'Koordinat Latitude wajib diisi.',
            'latitude.between' => 'Latitude harus berada dalam rentang -90 hingga 90 derajat.',
            'longitude.required' => 'Koordinat Longitude wajib diisi.',
            'longitude.between' => 'Longitude harus berada dalam rentang -180 hingga 180 derajat.',
            'radius.numeric' => 'Radius harus berupa angka (meter).',
            'prioritas.required' => 'Tingkat Prioritas (P1–P10) wajib dipilih/diisi.',
            'prioritas.integer' => 'Tingkat Prioritas harus berupa angka bulat antara 1 sampai 10.',
            'prioritas.between' => 'Tingkat Prioritas harus bernilai antara P1 sampai P10.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto yang diizinkan hanya JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran file foto tidak boleh melebihi 5 MB.',
        ];
    }
}
