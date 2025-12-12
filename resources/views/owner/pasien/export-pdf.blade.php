<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Pasien</title>
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
        
        .badge-male {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-female {
            background: #fce7f3;
            color: #9f1239;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .id-pasien {
            font-weight: 600;
            color: #806B3F;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR PASIEN</h1>
        <p>Klinik Kecantikan</p>
    </div>
    
    <div class="info-section">
        <div class="info-box">
            <strong>Tanggal Export:</strong> {{ $tanggal_export }}
        </div>
        <div class="info-box">
            <strong>Total Pasien:</strong> {{ $total_pasien }} orang
        </div>
    </div>
    
    @if($pasien->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">ID Pasien</th>
                <th style="width: 20%">Nama Lengkap</th>
                <th style="width: 13%">No Telepon</th>
                <th style="width: 12%">Tanggal Lahir</th>
                <th style="width: 8%">Usia</th>
                <th style="width: 10%">Gender</th>
                <th style="width: 22%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pasien as $index => $row)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td class="id-pasien">P-{{ str_pad($row->id_pasien, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $row->nama_lengkap }}</td>
                <td>{{ $row->no_telepon }}</td>
                <td>{{ $row->tanggal_lahir->format('d/m/Y') }}</td>
                <td style="text-align: center">{{ $row->usia }} th</td>
                <td>
                    @if($row->jenis_kelamin == 'L')
                        <span class="badge badge-male">Laki-laki</span>
                    @else
                        <span class="badge badge-female">Perempuan</span>
                    @endif
                </td>
                <td>{{ Str::limit($row->alamat, 40) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data pasien untuk diekspor</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | © {{ date('Y') }} Klinik Kecantikan</p>
    </div>
</body>
</html>