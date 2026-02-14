<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Brainstorming</title>
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
            width: 30%;
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
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
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
        <h1>Form Brainstorming</h1>
        <p>Tanggal: {{ date('d F Y') }}</p>
    </div>

    @foreach($row as $data)
    <div class="info-section">
        <table>
            <tr>
                <td class="field-label">Jenis Produk</td>
                <td class="field-value">{{ $data->jenis_produk ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Merk</td>
                <td class="field-value">{{ $data->merk ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Komposisi</td>
                <td class="field-value">{{ $data->komposisi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Varian Produk</td>
                <td class="field-value">{{ $data->varian ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Kelebihan Produk</td>
                <td class="field-value">{{ $data->kelebihan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Nama Perusahaan</td>
                <td class="field-value">{{ $data->namaUsaha ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">PIRT</td>
                <td class="field-value">{{ $data->PIRT ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Halal</td>
                <td class="field-value">{{ $data->Halal ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Legalitas Lainnya</td>
                <td class="field-value">{{ $data->legalitasLain ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Saran Penyajian</td>
                <td class="field-value">{{ $data->saranpenyajian ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Segmentasi</td>
                <td class="field-value">{{ $data->segmentasi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Jenis Kemasan</td>
                <td class="field-value">{{ $data->jeniskemasan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Kemasan Pendukung</td>
                <td class="field-value">{{ $data->harga ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Tagline</td>
                <td class="field-value">{{ $data->tagline ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Redaksi</td>
                <td class="field-value">{{ $data->redaksi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Gramasi</td>
                <td class="field-value">{{ $data->gramasi ?? '-' }}</td>
            </tr>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis pada {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
