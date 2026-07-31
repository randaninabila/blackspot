<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Blank Spot Sumatera Utara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 8px; color: #333; padding: 12px; }
        .header { text-align: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #234B26; }
        .header h1 { font-size: 12px; color: #234B26; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 11px; color: #234B26; font-weight: bold; }
        .header p { font-size: 8px; color: #666; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead { background-color: #234B26; color: white; }
        th { padding: 5px 3px; text-align: left; font-size: 7.5px; text-transform: uppercase; }
        td { padding: 4px 3px; border-bottom: 1px solid #e5e7eb; font-size: 7.5px; }
        tr:nth-child(even) td { background-color: #f8fafc; }
        .total { margin-top: 8px; font-size: 8.5px; color: #234B26; font-weight: bold; }
        
        .signature-container { width: 100%; margin-top: 25px; page-break-inside: avoid; }
        .signature-box { float: right; width: 230px; text-align: left; font-size: 8.5px; line-height: 1.3; }
        .signature-box .sig-date { margin-bottom: 2px; }
        .signature-box .sig-title { margin-bottom: 0; }
        .signature-space { height: 45px; }
        .signature-box .jabatan { text-align: left; font-weight: bold; }
        .signature-box .wilayah { text-align: center; font-weight: bold; margin-top: 2px; }
        .signature-box .nip { text-align: left; margin-top: 12px; }
        
        .footer { margin-top: 25px; text-align: left; font-size: 7px; color: #888; clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pendataan Blank Spot</h1>
        <h2>Provinsi Sumatera Utara</h2>
        <p>Dinas Komunikasi dan Informatika Provinsi Sumatera Utara</p>
        <p>Dicetak pada: {{ $tanggalCetak ?? date('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:12%">Kabupaten/Kota</th>
                <th style="width:10%">Kecamatan</th>
                <th style="width:10%">Desa</th>
                <th style="width:8%">Latitude</th>
                <th style="width:8%">Longitude</th>
                <th style="width:10%">Status Jaringan</th>
                <th style="width:6%">Prioritas</th>
                <th style="width:10%">Geografis</th>
                <th style="width:9%">Penduduk</th>
                <th style="width:6%">Jarak</th>
                <th style="width:4%">Tahun</th>
                <th style="width:4%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $row->kabupaten->nama_kabupaten ?? '-' }}</td>
                <td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
                <td>{{ $row->desa->nama_desa ?? '-' }}</td>
                <td style="text-align:center">{{ number_format($row->latitude, 5) }}</td>
                <td style="text-align:center">{{ number_format($row->longitude, 5) }}</td>
                <td>{{ $row->status_jaringan ?? '-' }}</td>
                <td style="text-align:center">{{ $row->prioritas ? 'P' . $row->prioritas : '-' }}</td>
                <td>{{ $row->kondisi_geografis ?? '-' }}</td>
                <td>{{ $row->jumlah_penduduk ?? '-' }}</td>
                <td style="text-align:center">{{ $row->jarak_ibukota ? $row->jarak_ibukota . ' km' : '-' }}</td>
                <td style="text-align:center">{{ $row->tahun }}</td>
                <td style="text-align:center">{{ $row->status_label }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" style="text-align:center;padding:20px;color:#999;">Tidak ada data untuk ditampilkan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total: {{ $data->count() }} titik blank spot</p>

    <div class="signature-container">
        <div class="signature-box">
            <p class="sig-date">{{ $namaKota }}, {{ $tanggalCetak }}</p>
            <p class="sig-title">Pejabat Berwenang / Penanggung Jawab,</p>
            <div class="signature-space"></div>
            <div class="jabatan">Kepala Dinas Komunikasi dan Informatika</div>
            <div class="wilayah">{{ $namaKabupaten }}</div>
            <div class="nip">NIP.{{ $nipFormatted }}</div>
        </div>
    </div>

    <div class="footer">
        * Laporan ini digenerate secara otomatis oleh Sistem Pendataan Blank Spot Sumatera Utara
    </div>
</body>
</html>