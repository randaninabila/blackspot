<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Verification Blank Spot</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #333; padding: 25px; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px double #234B26; }
        .header h1 { font-size: 14px; color: #234B26; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 12px; color: #234B26; font-weight: bold; }
        .header p { font-size: 9px; color: #666; margin-top: 4px; }
        .title { text-align: center; font-size: 13px; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        .table-info { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-info td { padding: 6px; font-size: 10px; vertical-align: top; }
        .table-info td.label { width: 30%; font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1; }
        .table-info td.val { width: 70%; border: 1px solid #cbd5e1; }
        
        .signature-container { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .signature-box { float: right; width: 280px; text-align: center; font-size: 9.5px; line-height: 1.4; }
        .signature-box .sig-date { text-align: center; margin-bottom: 8px; font-weight: normal; }
        .signature-box .jabatan { font-weight: bold; text-align: center; }
        .signature-box .wilayah { font-weight: bold; text-align: center; margin-top: 1px; }
        .signature-space { height: 60px; }
        .signature-box .kepala-dinas { text-align: center; font-weight: bold; }
        .signature-box .pangkat { text-align: center; font-size: 9px; color: #222; margin-top: 2px; }
        .signature-box .nip { text-align: center; margin-top: 2px; }
        
        .footer { margin-top: 40px; text-align: left; font-size: 8px; color: #888; clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH PROVINSI SUMATERA UTARA</h1>
        <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
        <p>Jl. H. M. Said No. 27 Medan, Sumatera Utara</p>
    </div>

    <div class="title">BERITA ACARA VERIFIKASI LAPANGAN BLANK SPOT</div>

    <p style="font-size: 10px; line-height: 1.5; margin-bottom: 15px;">
        Pada hari ini, tanggal <strong>{{ $tanggalCetak ?? date('d F Y') }}</strong>, telah dilakukan verifikasi dan validasi data lokasi blank spot dengan rincian data sebagai berikut:
    </p>

    <table class="table-info">
        <tr>
            <td class="label">ID Blank Spot</td>
            <td class="val">#{{ $blankSpot->id }}</td>
        </tr>
        <tr>
            <td class="label">Kabupaten / Kota</td>
            <td class="val">{{ $blankSpot->kabupaten->nama_kabupaten ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kecamatan</td>
            <td class="val">{{ $blankSpot->kecamatan->nama_kecamatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Desa / Kelurahan</td>
            <td class="val">{{ $blankSpot->desa->nama_desa ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Lokasi</td>
            <td class="val">{{ $blankSpot->nama_lokasi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Koordinat Geografis</td>
            <td class="val">Lat: {{ number_format($blankSpot->latitude, 6) }}, Lng: {{ number_format($blankSpot->longitude, 6) }}</td>
        </tr>
        <tr>
            <td class="label">Radius Cakupan</td>
            <td class="val">{{ $blankSpot->radius ? $blankSpot->radius . ' Meter' : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status Jaringan / Sinyal</td>
            <td class="val">{{ $blankSpot->status_jaringan ?? 'Tidak Ada Sinyal (Blank Spot)' }}</td>
        </tr>
        <tr>
            <td class="label">Tingkat Prioritas</td>
            <td class="val">{{ $blankSpot->prioritas ? 'P' . $blankSpot->prioritas : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kondisi Geografis</td>
            <td class="val">{{ $blankSpot->kondisi_geografis ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jumlah Penduduk</td>
            <td class="val">{{ $blankSpot->jumlah_penduduk ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jarak ke Ibu Kota</td>
            <td class="val">{{ $blankSpot->jarak_ibukota ? $blankSpot->jarak_ibukota . ' Km' : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tahun / Semester</td>
            <td class="val">{{ $blankSpot->tahun }} / Semester {{ $blankSpot->semester ?? 1 }}</td>
        </tr>
        <tr>
            <td class="label">Status Validasi</td>
            <td class="val"><strong>{{ strtoupper($blankSpot->status_label) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Petugas Verifikator</td>
            <td class="val">{{ $blankSpot->verifikator->nama ?? ($blankSpot->validator->nama ?? 'Tim Verifikasi Diskominfo') }}</td>
        </tr>
        <tr>
            <td class="label">Dokumentasi Foto</td>
            <td class="val">{{ $blankSpot->photos->count() }} Foto Lampiran Terdaftar</td>
        </tr>
        <tr>
            <td class="label">Hasil & Catatan Verifikasi</td>
            <td class="val">{{ $blankSpot->catatan_verifikasi ?? ($blankSpot->catatan_revisi ?? 'Verifikasi telah dilaksanakan sesuai prosedur teknis geospasial.') }}</td>
        </tr>
    </table>

    <div class="signature-container">
        <div class="signature-box">
            <p class="sig-date">{{ $namaKota }}, {{ $tanggalCetak }}</p>
            <div class="jabatan">Kepala Dinas Komunikasi dan Informatika</div>
            <div class="wilayah">{{ $namaWilayah }}</div>
            <div class="signature-space"></div>
            @if(!empty($namaKepalaDinas))
                <div class="kepala-dinas">{{ $namaKepalaDinas }}</div>
            @endif
            @if(!empty($pangkat))
                <div class="pangkat">{{ $pangkat }}</div>
            @endif
            @if(!empty($nipFormatted))
                <div class="nip">NIP. {{ $nipFormatted }}</div>
            @endif
        </div>
    </div>

    <div class="footer">
        * Berita Acara ini diterbitkan secara sah oleh Sistem Pendataan Blank Spot Dinas Komunikasi dan Informatika Provinsi Sumatera Utara.
    </div>
</body>
</html>
