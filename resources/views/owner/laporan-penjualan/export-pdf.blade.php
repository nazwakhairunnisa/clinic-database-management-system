<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Tahunan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #806B3F;
        }
        .header h1 {
            font-size: 26px;
            color: #806B3F;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .info-box {
            background: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .info-box strong { color: #806B3F; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        thead { background: #806B3F; color: white; }
        th {
            padding: 10px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 10px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
            text-align: center;
        }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .bg-income { background: #dbeafe !important; color: #1e40af; font-weight: bold; }
        .bg-expense { background: #fee2e2 !important; color: #991b1b; font-weight: bold; }
        .bg-profit { background: #d4edda !important; color: #166534; font-weight: bold; }
        .bg-profit-negative { background: #fee2e2 !important; color: #991b1b; font-weight: bold; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .section-title {
            background: #f1f5f9;
            font-weight: bold;
            font-size: 11px;
            padding: 12px 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN TAHUNAN</h1>
        <p>Klinik Kecantikan | Tahun {{ $tahun }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <strong>Tanggal Export:</strong> {{ $tanggal_export }}
        </div>
        <div class="info-box">
            <strong>Tahun Laporan:</strong> {{ $tahun }}
        </div>
        <div class="info-box">
            <strong>Total Laba Bersih:</strong> 
                <span style="color: {{ $monthlyData['totals']['profit'] >= 0 ? '#166534' : '#991b1b' }}">
                    Rp {{ number_format($monthlyData['totals']['profit'], 0, ',', '.') }}
                </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Kategori</th>
                @foreach($monthlyData['months'] as $item)
                <th>{{ $monthlyData['months'][$loop->index]['month_name'] }} {{ $tahun }}</th>
                @endforeach
                <th style="background: #c03838ff;">Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- PEMASUKAN -->
            <tr class="section-title"><td colspan="{{ count($monthlyData['months']) + 2 }}">PEMASUKAN</td></tr>
            <tr>
                <td class="text-left">Pembayaran Treatment</td>
                @foreach($monthlyData['months'] as $item)
                <td>Rp {{ number_format($item['income'], 0, ',', '.') }}</td>
                @endforeach
                <td class="bg-income">Rp {{ number_format($monthlyData['totals']['income'], 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-income">
                <td class="text-left">Total Pemasukan</td>
                @foreach($monthlyData['months'] as $item)
                <td>Rp {{ number_format($item['income'], 0, ',', '.') }}</td>
                @endforeach
                <td>Rp {{ number_format($monthlyData['totals']['income'], 0, ',', '.') }}</td>
            </tr>

            <!-- PENGELUARAN -->
            <tr class="section-title"><td colspan="{{ count($monthlyData['months']) + 2 }}">PENGELUARAN</td></tr>
            <tr>
                <td class="text-left">Pembelian Obat</td>
                @foreach($monthlyData['months'] as $item)
                <td>Rp {{ number_format($item['expense_pembelian_obat'] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="bg-expense">Rp {{ number_format($monthlyData['totals']['expense_pembelian_obat'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left">Pengeluaran Operasional</td>
                @foreach($monthlyData['months'] as $item)
                <td>Rp {{ number_format($item['expense_operasional'] ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="bg-expense">Rp {{ number_format($monthlyData['totals']['expense_operasional'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-expense">
                <td class="text-left">Total Pengeluaran</td>
                @foreach($monthlyData['months'] as $item)
                <td>Rp {{ number_format($item['expense'], 0, ',', '.') }}</td>
                @endforeach
                <td>Rp {{ number_format($monthlyData['totals']['expense'], 0, ',', '.') }}</td>
            </tr>

            <!-- LABA BERSIH -->
            <tr class="{{ $monthlyData['totals']['profit'] >= 0 ? 'bg-profit' : 'bg-profit-negative' }}">
                <td class="text-left">Laba Bersih (Net Profit)</td>
                @foreach($monthlyData['months'] as $item)
                <td style="color: {{ $item['profit'] >= 0 ? '#166534' : '#991b1b' }}">
                    Rp {{ number_format($item['profit'], 0, ',', '.') }}
                </td>
                @endforeach
                <td style="color: {{ $monthlyData['totals']['profit'] >= 0 ? '#166534' : '#991b1b' }}">
                    Rp {{ number_format($monthlyData['totals']['profit'], 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>