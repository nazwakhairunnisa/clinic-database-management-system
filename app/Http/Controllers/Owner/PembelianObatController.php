<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\PembelianObat;
use App\Models\StokObat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianObatController extends Controller
{
    public function index(Request $request)
    {
        $query = PembelianObat::with(['obat', 'supplier']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('obat', function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%");
            })->orWhereHas('supplier', function($q) use ($search) {
                $q->where('nama_supplier', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $pembelian = $query->orderBy('tanggal_beli', 'desc')
                        ->paginate(10);
        
        return view('owner.pembelian-obat.index', compact('pembelian'));
    }

    public function create()
    {
        $obatList = StokObat::orderBy('nama_obat')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        
        return view('owner.pembelian-obat.create', compact('obatList', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Validasi dengan conditional rules
        $rules = [
            'id_obat' => 'required|exists:stok_obat,id_obat',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum,lunas',
        ];

        $messages = [
            'id_obat.required' => 'Obat wajib dipilih',
            'id_supplier.required' => 'Supplier wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'status_pembayaran.required' => 'Status pembayaran wajib dipilih',
        ];

        // Conditional validation
        if ($request->status_pembayaran === 'lunas') {
            $rules['metode_pembayaran'] = 'required|in:cash,transfer,ewallet';
            $messages['metode_pembayaran.required'] = 'Metode pembayaran wajib dipilih jika sudah lunas';
        } else {
            $rules['metode_pembayaran'] = 'nullable';
            $rules['tanggal_jatuh_tempo'] = 'required|date|after_or_equal:today';
            $messages['tanggal_jatuh_tempo.required'] = 'Tanggal jatuh tempo wajib diisi jika belum lunas';
        }

        $validated = $request->validate($rules, $messages);

        try {
            // Tentukan metode_pembayaran: jika belum lunas → kirim NULL
            $metodePembayaran = $validated['status_pembayaran'] === 'lunas' 
                ? $validated['metode_pembayaran'] 
                : null;

            // Panggil stored procedure
            DB::statement('CALL TransaksiPembelianObat(?, ?, ?, ?, ?, ?, ?, ?, @id_pembelian_baru)', [
                $validated['id_obat'],
                $validated['id_supplier'],
                $validated['jumlah'],
                $validated['harga_satuan'],
                $validated['status_pembayaran'],
                $validated['status_pembayaran'] === 'belum' ? $validated['tanggal_jatuh_tempo'] : null,
                $metodePembayaran,  // BISA NULL jika belum lunas
                Auth::id()
            ]);

            $idPembelian = DB::select('SELECT @id_pembelian_baru as id')[0]->id;


            $message = 'Pembelian obat berhasil ditambahkan!';
            if ($validated['status_pembayaran'] === 'belum') {
                $message .= ' Pembayaran belum lunas, transaksi keuangan akan dicatat saat pelunasan.';
            }

            return redirect()
                ->route('owner.pembelian_obat.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error pembelian obat: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambah pembelian: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pembelian = PembelianObat::with(['obat', 'supplier'])->findOrFail($id);
        $obatList = StokObat::orderBy('nama_obat')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        
        return view('owner.pembelian-obat.edit', compact('pembelian', 'obatList', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        // Validasi dengan conditional rules
        $rules = [
            'id_obat' => 'required|exists:stok_obat,id_obat',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum,lunas',
        ];

        // Metode pembayaran dan tanggal jatuh tempo tergantung status
        if ($request->status_pembayaran == 'belum') {
            $rules['tanggal_jatuh_tempo'] = 'required|date';
            $rules['metode_pembayaran'] = 'nullable|in:cash,transfer,ewallet';
        } else {
            $rules['tanggal_jatuh_tempo'] = 'nullable|date';
            $rules['metode_pembayaran'] = 'required|in:cash,transfer,ewallet';
        }

        $validated = $request->validate($rules, [
            'id_obat.required' => 'Obat wajib dipilih',
            'id_supplier.required' => 'Supplier wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'status_pembayaran.required' => 'Status pembayaran wajib dipilih',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi untuk status lunas',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi untuk status belum lunas',
        ]);

        try {
            $pembelian = PembelianObat::findOrFail($id);
            $statusLama = $pembelian->status_pembayaran;
            $jumlahLama = $pembelian->jumlah;
            $obatLama = $pembelian->id_obat;

            // Jika ada perubahan obat, kembalikan stok obat lama
            if ($obatLama != $validated['id_obat']) {
                $stokObatLama = StokObat::findOrFail($obatLama);
                
                if ($stokObatLama->stok_terkini < $jumlahLama) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with('error', 'Tidak dapat mengubah obat karena stok obat lama sudah terpakai');
                }
                
                $stokObatLama->update([
                    'stok_terkini' => $stokObatLama->stok_terkini - $jumlahLama,
                    'tanggal_update' => now(),
                ]);

                // Tambah stok obat baru
                $stokObatBaru = StokObat::findOrFail($validated['id_obat']);
                $stokObatBaru->update([
                    'stok_terkini' => $stokObatBaru->stok_terkini + $validated['jumlah'],
                    'tanggal_update' => now(),
                ]);
            } else {
                // Jika obat sama, update selisih jumlah
                $selisihJumlah = $validated['jumlah'] - $jumlahLama;
                if ($selisihJumlah != 0) {
                    $stokObat = StokObat::findOrFail($validated['id_obat']);
                    
                    // Cek jika selisih negatif (mengurangi jumlah pembelian)
                    if ($selisihJumlah < 0 && $stokObat->stok_terkini < abs($selisihJumlah)) {
                        DB::rollBack();
                        return redirect()
                            ->back()
                            ->with('error', 'Tidak dapat mengurangi jumlah pembelian karena stok sudah terpakai');
                    }
                    
                    $stokObat->update([
                        'stok_terkini' => $stokObat->stok_terkini + $selisihJumlah,
                        'tanggal_update' => now(),
                    ]);
                }
            }

            // Update data pembelian terlebih dahulu
            $pembelian->update([
                'id_obat' => $validated['id_obat'],
                'id_supplier' => $validated['id_supplier'],
                'jumlah' => $validated['jumlah'],
                'harga_satuan' => $validated['harga_satuan'],
                'status_pembayaran' => $validated['status_pembayaran'],
                'tanggal_jatuh_tempo' => $validated['status_pembayaran'] == 'belum' ? $validated['tanggal_jatuh_tempo'] : null,
            ]);

            // Handle perubahan status pembayaran
            if ($statusLama != $validated['status_pembayaran']) {
                // Pastikan metode pembayaran ada untuk status lunas
                $metodePembayaran = $validated['metode_pembayaran'] ?? 'cash';
                
                // Panggil stored procedure untuk update status
                DB::statement('CALL UpdateStatusPembayaranObat(?, ?, ?, ?)', [
                    $id,
                    $validated['status_pembayaran'],
                    $metodePembayaran,
                    Auth::id()
                ]);
                
                // Refresh data pembelian untuk memastikan perubahan tersimpan
                $pembelian->refresh();
                
            } elseif ($validated['status_pembayaran'] == 'lunas') {
                // Jika status tetap lunas, update transaksi keuangan yang ada
                $transaksi = DB::table('transaksi_keuangan')
                    ->where('id_pembelian_obat', $id)
                    ->first();

                if ($transaksi) {
                    $totalPembelian = $validated['jumlah'] * $validated['harga_satuan'];
                    $obat = StokObat::findOrFail($validated['id_obat']);
                    $supplier = Supplier::findOrFail($validated['id_supplier']);

                    DB::table('transaksi_keuangan')
                        ->where('id_pembelian_obat', $id)
                        ->update([
                            'jumlah' => $totalPembelian,
                            'nama_transaksi' => 'Pembelian Obat: ' . $obat->nama_obat,
                            'metode_pembayaran' => $validated['metode_pembayaran'] ?? $transaksi->metode_pembayaran,
                            'keterangan' => "Pembelian {$validated['jumlah']} {$obat->satuan} {$obat->nama_obat} dari {$supplier->nama_supplier}",
                            'updated_at' => now(),
                        ]);
                }
            }

            $message = 'Data pembelian obat berhasil diperbarui';
            if ($statusLama == 'belum' && $validated['status_pembayaran'] == 'lunas') {
                $message .= '. Transaksi pengeluaran telah dicatat.';
            }

            return redirect()
                ->route('owner.pembelian_obat.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error update pembelian obat: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            

            $pembelian = PembelianObat::findOrFail($id);

            // Kurangi stok obat
            $stokObat = StokObat::findOrFail($pembelian->id_obat);
            
            // Cek apakah stok mencukupi untuk dikurangi
            if ($stokObat->stok_terkini < $pembelian->jumlah) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus pembelian karena stok sudah terpakai');
            }

            $stokObat->update([
                'stok_terkini' => $stokObat->stok_terkini - $pembelian->jumlah,
                'tanggal_update' => now(),
            ]);

            // Hapus transaksi keuangan terkait (jika ada)
            DB::table('transaksi_keuangan')
                ->where('id_pembelian_obat', $id)
                ->delete();

            // Hapus pembelian
            $pembelian->delete();

            

            return redirect()
                ->route('owner.pembelian_obat.index')
                ->with('success', 'Data pembelian obat berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error delete pembelian obat: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export pembelian obat ke PDF
     */
    public function export(Request $request)
    {
        try {
            $query = PembelianObat::with(['obat', 'supplier']);

            // Terapkan filter search jika ada
            if ($request->filled('search') && trim($request->search) !== '') {
                $search = trim($request->search);
                $query->whereHas('obat', function($q) use ($search) {
                    $q->where('nama_obat', 'like', "%{$search}%");
                })->orWhereHas('supplier', function($q) use ($search) {
                    $q->where('nama_supplier', 'like', "%{$search}%");
                });
            }

            // Terapkan filter status jika ada
            if ($request->filled('status')) {
                $query->where('status_pembayaran', $request->status);
            }

            $pembelian = $query->orderBy('tanggal_beli', 'desc')->get();

            $total_nilai = $pembelian->sum('total_harga');

            $pdf = Pdf::loadView('owner.pembelian-obat.export-pdf', [
                'pembelian'       => $pembelian,
                'tanggal_export'  => now()->format('d F Y'),
                'total_transaksi' => $pembelian->count(),
                'total_nilai'     => $total_nilai
            ]);

            $pdf->setPaper('a4', 'landscape');

            $filename = 'daftar_pembelian_obat_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Error export pembelian obat: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export data pembelian obat: ' . $e->getMessage());
        }
    }
}