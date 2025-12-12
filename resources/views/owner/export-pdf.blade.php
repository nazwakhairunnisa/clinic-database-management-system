<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Reservasi</title>
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
            margin-bottom: 15px;
            background: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            color: #806B3F;
            font-weight: 600;
        }
        
        .filter-info {
            background: #EED892;
            padding: 8px 12px;
            border-radius: 5px;
            margin-bottom: 15px;
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
        
        .badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        /* Status Badges */
        .badge-scheduled {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .badge-progress {
            background: #FEF3C7;
            color: #92400E;
        }
        
        .badge-completed {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge-cancelled {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        /* Payment Status */
        .badge-lunas {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge-belum {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .id-text {
            font-weight: 600;
            color: #806B3F;
        }
        
        .patient-name {
            font-weight: 600;
            color: #333;
        }
        
        .patient-phone {
            color: #666;
            font-size: 7px;
            margin-top: 2px;
        }
        
        .treatment-text {
            line-height: 1.4;
            max-width: 120px;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #888;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }
        
        .summary-box {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 9px;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
        
        .summary-item strong {
            color: #806B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>JADWAL RESERVASI</h1>
        <p>Klinik Kecantikan</p>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <div>
                <span class="info-label">Tanggal Export:</span> {{ $tanggal_export }}
            </div>
            <div>
                <span class="info-label">Total Reservasi:</span> {{ $total_reservasi }} booking
            </div>
        </div>
    </div>

    @if(count($filter_info) > 0)
    <div class="filter-info">
        <strong>Filter Aktif:</strong> {{ implode(' | ', $filter_info) }}
    </div>
    @endif
    
    @if($reservasi->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 8%">ID Reservasi</th>
                <th style="width: 7%">ID Pasien</th>
                <th style="width: 15%">Nama Pasien</th>
                <th style="width: 18%">Treatment</th>
                <th style="width: 11%">Tanggal</th>
                <th style="width: 6%">Jam</th>
                <th style="width: 10%">Status</th>
                <th style="width: 10%">Pembayaran</th>
                <th style="width: 12%">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasi as $index => $item)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td class="id-text">R-{{ str_pad($item->id_reservasi, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="id-text">P-{{ str_pad($item->id_pasien, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="patient-name">{{ $item->pasien->nama_depan }} {{ $item->pasien->nama_belakang }}</div>
                    <div class="patient-phone">{{ $item->pasien->no_telepon }}</div>
                </td>
                <td>
                    <div class="treatment-text">
                        {{ $item->treatments_list }}
                        @if($item->detailReservasi->count() > 1)
                        <span style="color: #666;">({{ $item->detailReservasi->count() }} items)</span>
                        @endif
                    </div>
                </td>
                <td>{{ $item->tanggal_reservasi->format('d M Y') }}</td>
                <td>{{ $item->jam_reservasi->format('H:i') }}</td>
                <td>
                    @php
                        $badgeClass = 'badge-scheduled';
                        if ($item->status == 'confirmed') $badgeClass = 'badge-progress';
                        if ($item->status == 'done' || $item->status == 'completed') $badgeClass = 'badge-completed';
                        if ($item->status == 'cancelled') $badgeClass = 'badge-cancelled';
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $item->status_badge['text'] }}
                    </span>
                </td>
                <td>
                    @if($item->pembayaran)
                        @php
                            $paymentBadge = $item->pembayaran->status_pembayaran == 'lunas' ? 'badge-lunas' : 'badge-belum';
                        @endphp
                        <span class="badge {{ $paymentBadge }}">
                            {{ ucfirst($item->pembayaran->status_pembayaran) }}
                        </span>
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                <td style="text-align: right; font-weight: 600;">
                    Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary --}}
    <div class="summary-box">
        @php
            $totalPendapatan = $reservasi->where('pembayaran.status_pembayaran', 'lunas')->sum('total_biaya');
            $totalBelumLunas = $reservasi->where('pembayaran.status_pembayaran', 'belum')->sum('total_biaya');
        @endphp
        <div class="summary-item">
            <strong>Total Pendapatan (Lunas):</strong> Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Belum Lunas:</strong> Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}
        </div>
    </div>
    
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data reservasi untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>