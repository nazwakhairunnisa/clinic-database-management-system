<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Stok Obat</title>
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
        
        .badge-habis { background: #f8d7da; color: #721c24; }
        .badge-sedikit { background: #fff3cd; color: #856404; }
        .badge-menipis { background: #fef3cd; color: #92700b; }
        .badge-aman { background: #d4edda; color: #155724; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .id-obat {
            font-weight: 600;
            color: #806B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR STOK OBAT</h1>
        <p>Klinik Kecantikan</p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <strong>Tanggal Export:</strong> {{ $tanggal_export }}
        </div>
        <div class="info-box">
            <strong>Total Obat:</strong> {{ $total_obat }} item
        </div>
        <div class="info-box">
            <strong>Obat Habis:</strong> {{ $obat_habis }} item
        </div>
    </div>
    
    @if($stokObat->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 6%">ID</th>
                <th style="width: 25%">Nama Obat</th>
                <th style="width: 10%">Satuan</th>
                <th style="width: 12%" class="text-center">Stok Awal</th>
                <th style="width: 12%" class="text-center">Stok Terkini</th>
                <th style="width: 12%">Status Stok</th>
                <th style="width: 13%">Last Update</th>
                <th style="width: 20%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stokObat as $row)
            <tr>
                <td class="id-obat">O-{{ str_pad($row->id_obat, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $row->nama_obat }}</td>
                <td>{{ $row->satuan }}</td>
                <td class="text-center">{{ number_format($row->stok_awal, 0, ',', '.') }}</td>
                <td class="text-center {{ $row->stok_terkini <= 10 ? 'text-red-600 font-bold' : '' }}">
                    {{ number_format($row->stok_terkini, 0, ',', '.') }}
                </td>
                <td>
                    @php
                        $badgeClass = match($row->status_stok) {
                            'Habis' => 'badge-habis',
                            'Sedikit Lagi' => 'badge-sedikit',
                            'Menipis' => 'badge-menipis',
                            'Aman' => 'badge-aman',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $row->status_stok }}</span>
                </td>
                <td>{{ $row->tanggal_update->format('d/m/Y') }}</td>
                <td>{{ Str::limit($row->deskripsi ?? '-', 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data stok obat untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>