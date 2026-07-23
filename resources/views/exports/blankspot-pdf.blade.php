<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Blank Spot Sumatera Utara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; color: #333; padding: 15px; }
        .header { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #234B26; }
        .header h1 { font-size: 13px; color: #234B26; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 12px; color: #234B26; font-weight: bold; }
        .header p { font-size: 8px; color: #666; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead { background-color: #234B26; color: white; }
        th { padding: 6px 4px; text-align: left; font-size: 8px; text-transform: uppercase; }
        td { padding: 5px 4px; border-bottom: 1px solid #e5e7eb; font-size: 8px; }
        tr:nth-child(even) td { background-color: #f8fafc; }
        .total { margin-top: 8px; font-size: 9px; color: #234B26; font-weight: bold; }
        
        .signature-container { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; font-size: 9px; }
        .signature-space { height: 60px; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        
        .footer { margin-top: 30px; text-align: left; font-size: 7px; color: #888; clear: both; }
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
                <th style="width:14%">Kabupaten/Kota</th>
                <th style="width:12%">Kecamatan</th>
                <th style="width:12%">Desa</th>
                <th style="width:9%">Latitude</th>
                <th style="width:9%">Longitude</th>
                <th style="width:7%">Radius (m)</th>
                <th style="width:12%">Status Jaringan</th>
                <th style="width:7%">Prioritas</th>
                <th style="width:6%">Tahun</th>
                <th style="width:9%">Status Validasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $row->kabupaten->nama_kabupaten ?? '-' }}</td>
                <td>{{ $row->kecamatan->nama_kecamatan ?? '-' }}</td>
                <td>{{ $row->desa->nama_desa ?? '-' }}</td>
                <td style="text-align:center">{{ number_format($row->latitude, 6) }}</td>
                <td style="text-align:center">{{ number_format($row->longitude, 6) }}</td>
                <td style="text-align:center">{{ $row->radius ?? '-' }}</td>
                <td>{{ $row->status_jaringan ?? ($row->keterangan ?? '-') }}</td>
                <td style="text-align:center">{{ $row->prioritas ? 'P' . $row->prioritas : '-' }}</td>
                <td style="text-align:center">{{ $row->tahun }}</td>
                <td style="text-align:center">{{ $row->status_label }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center;padding:20px;color:#999;">Tidak ada data untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total: {{ $data->count() }} titik blank spot</p>

    <div class="signature-container">
        <div class="signature-box">
            <p>Medan, {{ $tanggalCetak ?? date('d F Y') }}</p>
            <p>Pejabat Berwenang / Penanggung Jawab,</p>
            <div class="signature-space"></div>
            <p class="signature-name">{{ $namaPejabat ?? ($user->nama ?? 'Kepala Dinas Kominfo Sumut') }}</p>
            <p>NIP. {{ $nipPejabat ?? '19750812 200003 1 002' }}</p>
        </div>
    </div>

    <div class="footer">
        * Laporan ini digenerate secara otomatis oleh Sistem Pendataan Blank Spot Sumatera Utara.
    </div>
</body>
</html>