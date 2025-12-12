<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Pembelian Obat</title>
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
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
        }
        
        .badge-lunas {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-belum {
            background: #f8d7da;
            color: #721c24;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .id-pembelian {
            font-weight: 600;
            color: #806B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR PEMBELIAN OBAT</h1>
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
            <strong>Total Nilai Pembelian:</strong> Rp {{ number_format($total_nilai, 0, ',', '.') }}
        </div>
    </div>
    
    @if($pembelian->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 6%">ID</th>
                <th style="width: 18%">Nama Obat</th>
                <th style="width: 10%">Tgl Beli</th>
                <th style="width: 15%">Supplier</th>
                <th style="width: 9%" class="text-center">Jumlah</th>
                <th style="width: 12%" class="text-right">Harga Satuan</th>
                <th style="width: 12%" class="text-right">Total Harga</th>
                <th style="width: 9%">Status</th>
                <th style="width: 9%">Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian as $index => $row)
            <tr>
                <td class="id-pembelian">PO-{{ str_pad($row->id_pembelian_obat, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $row->obat->nama_obat ?? '-' }}</td>
                <td>{{ $row->tanggal_beli->format('d/m/Y') }}</td>
                <td>{{ $row->supplier->nama_supplier ?? '-' }}</td>
                <td class="text-center">
                    {{ number_format($row->jumlah, 0, ',', '.') }} {{ $row->obat->satuan ?? '' }}
                </td>
                <td class="text-right">Rp {{ number_format($row->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                <td>
                    @if($row->status_pembayaran == 'lunas')
                        <span class="badge badge-lunas">Lunas</span>
                    @else
                        <span class="badge badge-belum">Belum Dibayar</span>
                    @endif
                </td>
                <td>
                    @if($row->tanggal_jatuh_tempo)
                        {{ $row->tanggal_jatuh_tempo->format('d/m/Y') }}
                        @if($row->status_pembayaran == 'belum' && $row->tanggal_jatuh_tempo->isPast())
                            <span style="color: #dc3545; font-size: 8px; display: block;">(Terlambat)</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data pembelian obat untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>