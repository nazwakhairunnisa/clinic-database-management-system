<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
            font-size: 24px;
            color: #806B3F;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
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
            display: inline-block;
        }
        
        .info-box strong {
            color: #806B3F;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        thead {
            background: #806B3F;
            color: white;
        }
        
        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
        }
        
        .badge-cash { background: #d4edda; color: #155724; }
        .badge-transfer { background: #d1ecf1; color: #0c5460; }
        .badge-ewallet { background: #e2d4f8; color: #6a0dad; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .total-row {
            background: #806B3F !important;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENDAPATAN</h1>
        <p>Klinik Kecantikan</p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <strong>Tanggal Export:</strong> {{ $tanggal_export }}
        </div>
        <div class="info-box">
            <strong>Total Transaksi:</strong> {{ $total_transaksi }} transaksi
        </div>
        <div class="info-box">
            <strong>Total Pendapatan:</strong> Rp {{ number_format($total_pendapatan, 0, ',', '.') }}
        </div>
    </div>
    
    @if($pendapatan->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 6%">No</th>
                <th style="width: 28%">Nama Transaksi</th>
                <th style="width: 15%" class="text-right">Jumlah</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 15%">Metode</th>
                <th style="width: 21%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendapatan as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->nama_transaksi }}</td>
                <td class="text-right font-semibold">Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                <td>{{ $row->tanggal_transaksi->format('d/m/Y') }}</td>
                <td>
                    @php
                        $metode = $row->metode_pembayaran ?? 'cash';
                        $badgeClass = match($metode) {
                            'cash' => 'badge-cash',
                            'transfer' => 'badge-transfer',
                            'ewallet' => 'badge-ewallet',
                            default => 'bg-gray-100 text-gray-700'
                        };
                        $label = match($metode) {
                            'cash' => 'Cash',
                            'transfer' => 'Transfer',
                            'ewallet' => 'E-Wallet',
                            default => 'Unknown'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                </td>
                <td>{{ Str::limit($row->keterangan ?? '-', 50) }}</td>
            </tr>
            @endforeach
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data pendapatan untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>