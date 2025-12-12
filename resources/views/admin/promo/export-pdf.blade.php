<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Promo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #806B3F;
        }
        
        .header h1 {
            font-size: 22px;
            color: #806B3F;
            margin-bottom: 3px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .info-box {
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 5px;
            flex: 1;
            margin: 0 5px;
            display: inline-block;
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
            font-size: 8px;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 12px;
            font-weight: 700;
            color: #333;
        }
        
        .filter-info {
            background: #EED892;
            padding: 8px 12px;
            border-radius: 5px;
            margin-bottom: 12px;
            font-size: 9px;
        }
        
        .filter-info strong {
            color: #4A3B1C;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background: #806B3F;
            color: white;
        }
        
        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
        }
        
        td {
            padding: 7px 6px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 8px;
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .id-badge {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 8px;
            font-weight: 600;
        }
        
        .treatment-name {
            font-weight: 600;
            color: #333;
        }
        
        .promo-name {
            color: #666;
            font-size: 8px;
        }
        
        .price-normal {
            color: #666;
            text-decoration: line-through;
            font-size: 8px;
        }
        
        .price-promo {
            color: #16a34a;
            font-weight: 700;
            font-size: 9px;
        }
        
        .discount-badge {
            background: #fed7aa;
            color: #c2410c;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            display: inline-block;
        }
        
        .period-text {
            font-size: 8px;
            line-height: 1.3;
        }
        
        .period-separator {
            color: #999;
            font-size: 7px;
        }
        
        .status-badge {
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-upcoming {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-expired {
            background: #f3f4f6;
            color: #4b5563;
        }
        
        .status-detail {
            color: #666;
            font-size: 7px;
            margin-top: 2px;
        }
        
        .image-placeholder {
            width: 45px;
            height: 45px;
            background: #f0f0f0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 16px;
        }
        
        .promo-image {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #888;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }
        
        .summary-section {
            background: #f5f5f5;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .summary-title {
            font-size: 11px;
            font-weight: 700;
            color: #806B3F;
            margin-bottom: 8px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        
        .summary-item {
            text-align: center;
            padding: 8px;
            background: white;
            border-radius: 5px;
        }
        
        .summary-item-label {
            font-size: 8px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .summary-item-value {
            font-size: 12px;
            font-weight: 700;
            color: #806B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR PROMO</h1>
        <p>Klinik Kecantikan</p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <div class="info-label">Tanggal Export</div>
            <div class="info-value" style="font-size: 10px;">{{ $tanggal_export }}</div>
        </div>
        <div class="info-box">
            <div class="info-label">Total Promo</div>
            <div class="info-value">{{ $total_promos }} Promo</div>
        </div>
        <div class="info-box">
            <div class="info-label">Promo Aktif</div>
            <div class="info-value">{{ $active_promos }} Aktif</div>
        </div>
    </div>

    @if($search_query || $date_filter)
    <div class="filter-info">
        <strong>Filter Aktif:</strong>
        @if($search_query)
            Pencarian: "{{ $search_query }}"
        @endif
        @if($search_query && $date_filter) | @endif
        @if($date_filter)
            Tanggal: {{ \Carbon\Carbon::parse($date_filter)->format('d M Y') }}
        @endif
    </div>
    @endif
    
    @if($promos->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 7%">ID</th>
                <th style="width: 6%">Gambar</th>
                <th style="width: 15%">Treatment</th>
                <th style="width: 15%">Nama Promo</th>
                <th style="width: 10%">Harga</th>
                <th style="width: 10%">Hemat</th>
                <th style="width: 15%">Periode</th>
                <th style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promos as $index => $promo)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>
                    <span class="id-badge">
                        P{{ str_pad($promo->promo_id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if($promo->gambar_promo)
                        <img src="{{ public_path('storage/' . $promo->gambar_promo) }}" 
                             alt="{{ $promo->nama_promo }}" 
                             class="promo-image">
                    @else
                        <div class="image-placeholder">-</div>
                    @endif
                </td>
                <td>
                    <div class="treatment-name">
                        {{ $promo->treatment->nama_treatment ?? '-' }}
                    </div>
                </td>
                <td>
                    <div class="promo-name">{{ $promo->nama_promo }}</div>
                </td>
                <td>
                    @if($promo->treatment)
                        <div class="price-normal">{{ $promo->treatment->formatted_harga }}</div>
                        <div class="price-promo">{{ $promo->formatted_harga_promo }}</div>
                    @else
                        <div>-</div>
                    @endif
                </td>
                <td>
                    @if($promo->treatment)
                        <div class="discount-badge">
                            Hemat {{ $promo->persen_diskon }}%
                        </div>
                        <div style="margin-top: 3px; font-size: 8px; color: #666;">
                            Rp {{ number_format($promo->hemat, 0, ',', '.') }}
                        </div>
                    @else
                        <div>-</div>
                    @endif
                </td>
                <td>
                    <div class="period-text">
                        <div>{{ $promo->periode_mulai->format('d M Y') }}</div>
                        <div class="period-separator">s/d</div>
                        <div>{{ $promo->periode_selesai->format('d M Y') }}</div>
                    </div>
                </td>
                <td>
                    @if($promo->isActive())
                        <span class="status-badge status-active">Aktif</span>
                        <div class="status-detail">{{ $promo->sisa_hari }} hari lagi</div>
                    @elseif($promo->periode_mulai > now())
                        <span class="status-badge status-upcoming">Akan Datang</span>
                    @else
                        <span class="status-badge status-expired">Berakhir</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-title">Ringkasan Data Promo</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-item-label">Total Promo</div>
                <div class="summary-item-value">{{ $total_promos }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Promo Aktif</div>
                <div class="summary-item-value">{{ $active_promos }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Total Hemat</div>
                <div class="summary-item-value" style="font-size: 10px;">
                    Rp {{ number_format($total_hemat, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Rata-rata Diskon</div>
                <div class="summary-item-value">{{ round($avg_diskon) }}%</div>
            </div>
        </div>
    </div>
    
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p style="font-size: 16px;">Tidak ada data promo untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>