<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Blank Spot Sumatera Utara</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #234B26; }
        .header h1 { font-size: 14px; color: #234B26; font-weight: bold; text-transform: uppercase; }
        .header h2 { font-size: 13px; color: #234B26; font-weight: bold; }
        .header p { font-size: 9px; color: #666; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        thead { background-color: #234B26; color: white; }
        th { padding: 7px 5px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 5px 5px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        tr:nth-child(even) td { background-color: #f3f3e8; }
        .footer { margin-top: 20px; text-align: right; font-size: 8px; color: #888; }
        .total { margin-top: 8px; font-size: 10px; color: #234B26; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Blank Spot</h1>
        <h2>Provinsi Sumatera Utara</h2>
        <p>Dinas Komunikasi dan Informatika Provinsi Sumatera Utara</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:16%">Kabupaten/Kota</th>
                <th style="width:15%">Kecamatan</th>
                <th style="width:15%">Desa</th>
                <th style="width:11%">Latitude</th>
                <th style="width:11%">Longitude</th>
                <th style="width:7%">Tahun</th>
                <th style="width:21%">Keterangan</th>
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
                <td style="text-align:center">{{ $row->tahun }}</td>
                <td>{{ $row->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:20px;color:#999;">Tidak ada data untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total: {{ $data->count() }} titik blank spot</p>
    <div class="footer">
        Laporan ini digenerate secara otomatis oleh Sistem Pendataan Blank Spot Sumatera Utara
    </div>
</body>
</html>