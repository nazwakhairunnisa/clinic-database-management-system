<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $pembayaran->reservasi->pasien->nama_depan }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.5;
            padding: 20px;
            max-width: 80mm;
            margin: 0 auto;
        }

        .receipt {
            border: 2px dashed #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            margin: 2px 0;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
            padding-bottom: 3px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .row-label {
            font-weight: normal;
        }

        .row-value {
            font-weight: bold;
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 5px 0;
            border-bottom: 1px dashed #ccc;
        }

        .items-table td:last-child,
        .items-table th:last-child {
            text-align: right;
        }

        .total-section {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 16px;
            border-top: 1px solid #000;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 15px;
            margin-top: 15px;
        }

        .footer p {
            font-size: 10px;
            margin: 3px 0;
        }

        .signature {
            margin-top: 30px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin: 50px auto 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border: 2px solid #000;
            font-weight: bold;
            margin: 10px 0;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
            }

            .receipt {
                border: none;
            }

            .no-print {
                display: none;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

    {{-- PRINT BUTTON --}}
    <button onclick="window.print()" class="print-button no-print">
        🖨️ PRINT STRUK
    </button>

    <div class="receipt">
        
        {{-- HEADER --}}
        <div class="header">
            <h1>Clay Skinthetic Clinic</h1>
            <p>Klinik Kecantikan & Perawatan Kulit</p>
            <p>Jl. Jendral Ahmad Yani (Kp. Kruni), Stabat</p>
            <p>Telp: 0821 6183 5144</p>
        </div>

        {{-- STRUK INFO --}}
        <div class="section">
            <div class="row">
                <span class="row-label">No. Struk:</span>
                <span class="row-value">#{{ str_pad($pembayaran->id_pembayaran, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="row">
                <span class="row-label">Tanggal:</span>
                <span class="row-value">{{ $pembayaran->tanggal_pembayaran->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Kasir:</span>
                <span class="row-value">{{ $pembayaran->user->username ?? 'Admin' }}</span>
            </div>
        </div>

        {{-- CUSTOMER INFO --}}
        <div class="section">
            <div class="section-title">INFORMASI PASIEN</div>
            <div class="row">
                <span class="row-label">ID Pasien:</span>
                <span class="row-value">P-{{ str_pad($pembayaran->reservasi->pasien->id_pasien, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="row">
                <span class="row-label">Nama:</span>
                <span class="row-value">
                    {{ $pembayaran->reservasi->pasien->nama_depan }} 
                    {{ $pembayaran->reservasi->pasien->nama_belakang }}
                </span>
            </div>
            <div class="row">
                <span class="row-label">Telepon:</span>
                <span class="row-value">{{ $pembayaran->reservasi->pasien->no_telepon }}</span>
            </div>
            <div class="row">
                <span class="row-label">Tgl Treatment:</span>
                <span class="row-value">{{ $pembayaran->reservasi->tanggal_reservasi->format('d/m/Y') }}</span>
            </div>
        </div>

        {{-- ITEMS / TREATMENT --}}
        <div class="section">
            <div class="section-title">DETAIL TREATMENT</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Item</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 35%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayaran->reservasi->detailReservasi as $detail)
                    <tr>
                        <td>{{ $detail->treatment->nama_treatment }}</td>
                        <td style="text-align: center;">{{ $detail->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->harga_saat_reservasi * $detail->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- TOTAL SECTION --}}
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Diskon:</span>
                <span>Rp 0</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- PAYMENT METHOD --}}
        <div class="section">
            <div class="section-title">METODE PEMBAYARAN</div>
            <div class="row">
                <span class="row-label">Metode:</span>
                <span class="row-value" style="text-transform: uppercase;">
                    {{ $pembayaran->metode_pembayaran == 'cash' ? '💵 TUNAI' : '' }}
                    {{ $pembayaran->metode_pembayaran == 'transfer' ? '🏦 TRANSFER BANK' : '' }}
                    {{ $pembayaran->metode_pembayaran == 'ewallet' ? '📱 E-WALLET' : '' }}
                </span>
            </div>
        </div>

        {{-- STATUS --}}
        <div class="section" style="text-align: center;">
            <div class="status-badge">
                ✅ LUNAS
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <p>*** TERIMA KASIH ***</p>
            <p>Atas kunjungan Anda</p>
            <p>Semoga lekas sembuh & sehat selalu</p>
            <p style="margin-top: 10px;">Follow us:</p>
            <p>@msbdclinic | www.msbdclinic.com</p>
            <p style="margin-top: 10px; font-size: 9px; font-style: italic;">
                Struk ini adalah bukti pembayaran yang sah.<br>
                Barang yang sudah dibeli tidak dapat dikembalikan.
            </p>
        </div>

        {{-- SIGNATURE (Optional) --}}
        <div class="signature">
            <div class="signature-line"></div>
            <p>Tanda Tangan Kasir</p>
        </div>

    </div>

    {{-- AUTO PRINT SCRIPT --}}
    <script>
        // Uncomment jika mau auto print saat halaman dibuka
        // window.onload = function() {
        //     window.print();
        // }

        // Close window setelah print (optional)
        window.onafterprint = function() {
            // window.close();
        }
    </script>

</body>
</html>