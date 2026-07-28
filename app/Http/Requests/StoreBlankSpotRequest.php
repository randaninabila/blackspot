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
                return false;
            }
            return true;
        }

        return false;
    }

    protected function prepareForValidation(): void
    {
        $user = Auth::user();

        $statusJaringan = $this->input('status_jaringan');
        $rawPrioritas   = $this->input('prioritas');

        $priorityToStatusMap = [
            1 => 'Zero Blankspot',
            2 => 'Sinyal Sangat Lemah',
            3 => 'Sinyal Lemah',
            4 => '2G',
            5 => '3G',
            6 => '4G Tidak Stabil',
        ];

        if (empty($statusJaringan)) {
            if (!empty($rawPrioritas) && is_numeric($rawPrioritas) && isset($priorityToStatusMap[(int)$rawPrioritas])) {
                $statusJaringan = $priorityToStatusMap[(int)$rawPrioritas];
            } else {
                $statusJaringan = 'Blank Spot Total';
            }
        }

        // ALWAYS calculate priority automatically from status_jaringan
        $prioritas = BlankSpot::getPrioritasFromStatusJaringan($statusJaringan);

        $toMerge = [
            'status_jaringan' => $statusJaringan,
            'prioritas'       => $prioritas,
        ];

        if ($user && $user->isOperator()) {
            $toMerge['kabupaten_id'] = $user->kabupaten_id;
        }

        $this->merge($toMerge);
    }

    public function rules(): array
    {
        $user = Auth::user();

        $fotoRule = is_array($this->file('foto')) ? 'nullable|array|max:10' : 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120';

        return [
            'kabupaten_id'      => $user && $user->isOperator() ? 'nullable' : 'required|exists:kabupaten,id',
            'kecamatan_id'      => 'required|exists:kecamatan,id',
            'desa_id'           => 'nullable',
            'nama_desa'         => 'nullable|string|max:255',
            'desa'              => 'nullable|string|max:255',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'radius'            => 'nullable|numeric|min:0',
            'status_jaringan'   => 'required|string|max:255',
            'kondisi_geografis' => 'required|string|max:255',
            'jumlah_penduduk'   => 'required|string|max:255',
            'jarak_ibukota'     => 'required|numeric|min:0',
            'prioritas'         => 'nullable|integer|between:1,10',
            'tahun'             => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'semester'          => 'nullable|integer|between:1,2',
            'foto'              => $fotoRule,
            'foto.*'            => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120',
            'photos'            => 'nullable|array|max:10',
            'photos.*'          => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:5120',
            'nama_lokasi'       => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $kabupatenId = $this->input('kabupaten_id') ?? ($user && $user->isOperator() ? $user->kabupaten_id : null);
            $kecamatanId = $this->input('kecamatan_id');
            $desaId      = $this->input('desa_id');

            if ($kabupatenId && $kecamatanId) {
                $kecamatan = \App\Models\Kecamatan::find($kecamatanId);
                if ($kecamatan && (int) $kecamatan->kabupaten_id !== (int) $kabupatenId) {
                    $validator->errors()->add('kecamatan_id', 'Kecamatan yang dipilih tidak sesuai dengan Kabupaten/Kota terpilih.');
                }
            }

            if ($kecamatanId && $desaId && is_numeric($desaId)) {
                $desa = \App\Models\Desa::find($desaId);
                if ($desa && (int) $desa->kecamatan_id !== (int) $kecamatanId) {
                    $validator->errors()->add('desa_id', 'Desa yang dipilih tidak sesuai dengan Kecamatan terpilih.');
                }
            }

            $fotoFiles   = $this->file('foto');
            $photosFiles = $this->file('photos');

            $countFoto   = is_array($fotoFiles) ? count($fotoFiles) : ($fotoFiles ? 1 : 0);
            $countPhotos = is_array($photosFiles) ? count($photosFiles) : ($photosFiles ? 1 : 0);
            $totalUploaded = $countFoto + $countPhotos;

            if ($totalUploaded === 0) {
                $validator->errors()->add('photos', 'Minimal terdapat 1 foto yang diunggah.');
            }

            if ($totalUploaded > 10) {
                $validator->errors()->add('photos', 'Jumlah foto yang diunggah tidak boleh lebih dari 10 file.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'kabupaten_id.required'      => 'Kabupaten/Kota wajib dipilih.',
            'kabupaten_id.exists'        => 'Kabupaten/Kota tidak valid.',
            'kecamatan_id.required'      => 'Kecamatan wajib dipilih.',
            'kecamatan_id.exists'        => 'Kecamatan tidak valid.',
            'status_jaringan.required'   => 'Status jaringan wajib dipilih.',
            'kondisi_geografis.required' => 'Kondisi geografis wajib dipilih.',
            'jumlah_penduduk.required'   => 'Jumlah penduduk wajib dipilih.',
            'jarak_ibukota.required'     => 'Jarak ke ibu kota wajib diisi.',
            'jarak_ibukota.numeric'      => 'Jarak ke ibu kota harus berupa angka numerik.',
            'jarak_ibukota.min'          => 'Jarak ke ibu kota tidak boleh bernilai negatif.',
            'latitude.required'          => 'Koordinat Latitude wajib diisi.',
            'latitude.numeric'           => 'Latitude harus berupa angka.',
            'latitude.between'           => 'Latitude harus berada dalam rentang -90 hingga 90 derajat.',
            'longitude.required'         => 'Koordinat Longitude wajib diisi.',
            'longitude.numeric'          => 'Longitude harus berupa angka.',
            'longitude.between'          => 'Longitude harus berada dalam rentang -180 hingga 180 derajat.',
            'tahun.integer'              => 'Tahun harus berupa angka tahun.',
            'tahun.min'                  => 'Tahun minimal adalah 2000.',
            'tahun.max'                  => 'Tahun tidak boleh melebihi tahun depan.',
            'foto.image'                 => 'File foto harus berupa gambar.',
            'foto.mimes'                 => 'Format foto yang diizinkan hanya JPG, JPEG, PNG, atau WEBP.',
            'foto.max'                   => 'Ukuran file foto tidak boleh melebihi 5 MB per file.',
            'foto.*.image'               => 'Setiap file foto harus berupa gambar.',
            'foto.*.mimes'               => 'Format foto yang diizinkan hanya JPG, JPEG, PNG, atau WEBP.',
            'foto.*.max'                 => 'Ukuran masing-masing foto tidak boleh melebihi 5 MB.',
            'photos.max'                 => 'Jumlah foto tidak boleh melebihi 10 file.',
            'photos.*.image'             => 'Setiap file foto harus berupa gambar.',
            'photos.*.mimes'             => 'Format foto yang diizinkan hanya JPG, JPEG, PNG, atau WEBP.',
            'photos.*.max'               => 'Ukuran masing-masing foto tidak boleh melebihi 5 MB.',
        ];
    }
}
