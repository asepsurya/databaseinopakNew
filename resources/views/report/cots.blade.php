<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan COTS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .ikm-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .ikm-info h3 {
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h2 {
            font-size: 16px;
            background-color: #f5f5f5;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-left: 4px solid #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px;
            vertical-align: top;
        }
        .field-label {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 25%;
        }
        .field-value {
            width: 75%;
        }
        .dokumentasi {
            margin-top: 30px;
        }
        .dokumentasi h2 {
            font-size: 16px;
            margin-bottom: 15px;
            padding: 8px 12px;
            background-color: #f5f5f5;
            border-left: 4px solid #333;
        }
        .dokumentasi-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .dokumentasi-item {
            width: 150px;
            text-align: center;
        }
        .dokumentasi-item img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        @page {
            margin: 2cm;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Coaching on The Spot (COTS)</h1>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    @foreach($row as $data)
    <!-- Informasi IKM -->
    <div class="ikm-info">
        <h3>Informasi IKM</h3>
        <table>
            <tr>
                <td class="field-label">Nama</td>
                <td class="field-value">{{ $data->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Nama Produk</td>
                <td class="field-value">{{ $data->NamaProduk ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Merk</td>
                <td class="field-value">{{ $data->merk ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Alamat</td>
                <td class="field-value">
                    {{ $data->alamat ?? '-' }}
                    @if(isset($data->desa)), {{ $data->desa ?? '' }}@endif
                    @if(isset($data->kecamatan)), {{ $data->kecamatan ?? '' }}@endif
                    @if(isset($data->kota)), {{ $data->kota ?? '' }}@endif
                    @if(isset($data->provinsi)), {{ $data->provinsi ?? '' }}@endif
                </td>
            </tr>
            <tr>
                <td class="field-label">No. HP</td>
                <td class="field-value">{{ $data->no_hp ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Data COTS -->
    <div class="info-section">
        <h2>Data Coaching on The Spot</h2>
        <table>
            <tr>
                <td class="field-label">Sejarah Singkat</td>
                <td class="field-value">{{ $data->sejarahSingkat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Produk yang Dijual</td>
                <td class="field-value">{{ $data->produkjual ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Bahan Baku</td>
                <td class="field-value">{{ $data->bahanbaku ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Cara Pemasaran</td>
                <td class="field-value">{{ $data->carapemasaran ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Proses Produksi</td>
                <td class="field-value">{{ $data->prosesproduksi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Omset</td>
                <td class="field-value">{{ $data->omset ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Kapasitas Produksi</td>
                <td class="field-value">{{ $data->kapasitasproduksi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Kendala</td>
                <td class="field-value">{{ $data->kendala ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Solusi</td>
                <td class="field-value">{{ $data->solusi ?? '-' }}</td>
            </tr>
        </table>
    </div>
    @endforeach

    <!-- Dokumentasi Foto -->
    @if($dokumentasi && count($dokumentasi) > 0)
    <div class="dokumentasi">
        <h2>Dokumentasi Foto</h2>
        <div class="dokumentasi-grid">
            @foreach($dokumentasi as $doc)
            <div class="dokumentasi-item">
                <img src="{{ public_path('storage/' . $doc->gambar) }}" alt="Dokumentasi">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis pada {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
