<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Treatment</title>
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
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
            flex: 1;
            margin: 0 5px;
        }
        
        .info-box:first-child {
            margin-left: 0;
        }
        
        .info-box:last-child {
            margin-right: 0;
        }
        
        .info-label {
            color: #806B3F;
            font-weight: 600;
            font-size: 9px;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }
        
        .search-info {
            background: #EED892;
            padding: 8px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .search-info strong {
            color: #4A3B1C;
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
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9px;
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .id-badge {
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 8px;
            font-weight: 600;
        }
        
        .treatment-name {
            font-weight: 600;
            color: #333;
            font-size: 10px;
        }
        
        .price {
            font-weight: 700;
            color: #806B3F;
            font-size: 10px;
        }
        
        .duration {
            color: #666;
            font-size: 9px;
        }
        
        .description {
            color: #666;
            line-height: 1.4;
            font-size: 8px;
        }
        
        .image-placeholder {
            width: 50px;
            height: 50px;
            background: #f0f0f0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 18px;
        }
        
        .treatment-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .summary-section {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .summary-title {
            font-size: 12px;
            font-weight: 700;
            color: #806B3F;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        
        .summary-item-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-item-value {
            font-size: 14px;
            font-weight: 700;
            color: #806B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR TREATMENT</h1>
        <p>Klinik Kecantikan</p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <div class="info-label">Tanggal Export</div>
            <div class="info-value" style="font-size: 11px;">{{ $tanggal_export }}</div>
        </div>
        <div class="info-box">
            <div class="info-label">Total Treatment</div>
            <div class="info-value">{{ $total_treatments }} Items</div>
        </div>
    </div>

    @if($search_query)
    <div class="search-info">
        <strong>Filter Pencarian:</strong> "{{ $search_query }}"
    </div>
    @endif
    
    @if($treatments->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 8%">ID</th>
                <th style="width: 8%">Gambar</th>
                <th style="width: 22%">Nama Treatment</th>
                <th style="width: 12%">Harga</th>
                <th style="width: 10%">Durasi</th>
                <th style="width: 35%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatments as $index => $treatment)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>
                    <span class="id-badge">
                        T{{ str_pad($treatment->id_treatment, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if($treatment->foto_treatment)
                        <img src="{{ public_path('storage/' . $treatment->foto_treatment) }}" 
                             alt="{{ $treatment->nama_treatment }}" 
                             class="treatment-image">
                    @else
                        <div class="image-placeholder">📷</div>
                    @endif
                </td>
                <td>
                    <div class="treatment-name">{{ $treatment->nama_treatment }}</div>
                </td>
                <td>
                    <div class="price">{{ $treatment->formatted_harga }}</div>
                </td>
                <td>
                    <div class="duration">{{ $treatment->durasi }} menit</div>
                </td>
                <td>
                    <div class="description">
                        {{ $treatment->deskripsi ?? '-' }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-title"> Ringkasan Data Treatment</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-item-label">Total Treatment</div>
                <div class="summary-item-value">{{ $total_treatments }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Rata-rata Harga</div>
                <div class="summary-item-value" style="font-size: 11px;">
                    Rp {{ number_format($avg_harga, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Total Nilai Treatment</div>
                <div class="summary-item-value" style="font-size: 11px;">
                    Rp {{ number_format($total_harga, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Rata-rata Durasi</div>
                <div class="summary-item-value">{{ round($avg_durasi) }} menit</div>
            </div>
        </div>
    </div>
    
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p style="font-size: 16px;"></p>
        <p>Tidak ada data treatment untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>