<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    /**
     * Display laporan penjualan dengan filter bulan & tahun
     */
    public function index(Request $request)
    {
        // Get filter parameters (default: bulan & tahun sekarang)
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        
        // Validate inputs
        $bulan = (int) $bulan;
        $tahun = (int) $tahun;
        
        try {
            // 1. GET SUMMARY DATA untuk bulan yang dipilih menggunakan Stored Procedure
            $summaryData = $this->getSummaryData($bulan, $tahun);
            
            // 2. GET COMPARISON DATA (bulan sebelumnya)
            $previousMonth = $bulan == 1 ? 12 : $bulan - 1;
            $previousYear = $bulan == 1 ? $tahun - 1 : $tahun;
            $comparisonData = $this->getSummaryData($previousMonth, $previousYear);
            
            // 3. CALCULATE PERCENTAGE CHANGES
            $stats = $this->calculateStats($summaryData, $comparisonData);
            
            // 4. GET DATA PER BULAN (untuk tabel horizontal 12 bulan)
            $monthlyData = $this->getMonthlyData($tahun);
            
            // 5. GET BREAKDOWN DETAIL
            $breakdown = $this->getBreakdownDetail($bulan, $tahun);
            
            return view('owner.laporan-penjualan.index', compact(
                'stats', 
                'monthlyData', 
                'breakdown',
                'bulan',
                'tahun'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error laporan penjualan: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }
    
    /**
     * Get summary data menggunakan Stored Procedure LaporanKeuanganBulanan
     */
    private function getSummaryData($bulan, $tahun)
    {
        // Call Stored Procedure
        DB::statement('SET @total_pemasukan = 0, @total_pengeluaran = 0, @saldo = 0');
        
        $result = DB::select('CALL LaporanKeuanganBulanan(?, ?, @total_pemasukan, @total_pengeluaran, @saldo)', [
            $bulan,
            $tahun
        ]);
        
        // Get output parameters
        $output = DB::select('SELECT @total_pemasukan as total_pemasukan, 
                                     @total_pengeluaran as total_pengeluaran, 
                                     @saldo as saldo')[0];
        
        return [
            'total_pemasukan' => (float) $output->total_pemasukan,
            'total_pengeluaran' => (float) $output->total_pengeluaran,
            'saldo' => (float) $output->saldo
        ];
    }
    
    /**
     * Calculate statistics dan comparison percentage
     */
    private function calculateStats($current, $previous)
    {
        // Calculate percentage changes
        $incomeChange = $this->calculatePercentageChange(
            $previous['total_pemasukan'], 
            $current['total_pemasukan']
        );
        
        $expenseChange = $this->calculatePercentageChange(
            $previous['total_pengeluaran'], 
            $current['total_pengeluaran']
        );
        
        $profitChange = $this->calculatePercentageChange(
            $previous['saldo'], 
            $current['saldo']
        );
        
        return [
            'income' => [
                'value' => $current['total_pemasukan'],
                'formatted' => 'Rp ' . number_format($current['total_pemasukan'], 0, ',', '.'),
                'change' => $incomeChange['percentage'],
                'direction' => $incomeChange['direction'],
                'color' => $incomeChange['direction'] == 'up' ? 'green' : 'red'
            ],
            'expense' => [
                'value' => $current['total_pengeluaran'],
                'formatted' => 'Rp ' . number_format($current['total_pengeluaran'], 0, ',', '.'),
                'change' => $expenseChange['percentage'],
                'direction' => $expenseChange['direction'],
                'color' => $expenseChange['direction'] == 'up' ? 'red' : 'green' // Expense naik = merah
            ],
            'profit' => [
                'value' => $current['saldo'],
                'formatted' => 'Rp ' . number_format($current['saldo'], 0, ',', '.'),
                'change' => $profitChange['percentage'],
                'direction' => $profitChange['direction'],
                'color' => $profitChange['direction'] == 'up' ? 'green' : 'red'
            ]
        ];
    }
    
    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return [
                'percentage' => $newValue > 0 ? 100 : 0,
                'direction' => $newValue >= 0 ? 'up' : 'down'
            ];
        }
        
        $change = (($newValue - $oldValue) / abs($oldValue)) * 100;
        
        return [
            'percentage' => abs(round($change, 2)),
            'direction' => $change >= 0 ? 'up' : 'down'
        ];
    }
    
    /**
     * Get data per bulan untuk 12 bulan (tabel horizontal)
     */
    private function getMonthlyData($tahun)
    {
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $data = $this->getSummaryData($month, $tahun);
            
            // Hitung breakdown pengeluaran per bulan
            $breakdown = $this->getBreakdownDetail($month, $tahun);
            
            $monthlyData[] = [
                'month' => $month,
                'year' => $tahun,
                'income' => $data['total_pemasukan'],
                'expense' => $data['total_pengeluaran'],
                'profit' => $data['saldo'],
                // TAMBAHAN: breakdown per bulan
                'expense_pembelian_obat' => $breakdown['expense']['pembelian_obat'] ?? 0,
                'expense_operasional' => $breakdown['expense']['operasional'] ?? 0,
            ];
        }
        
        // Hitung total per kategori
        $totalPembelianObat = array_sum(array_column($monthlyData, 'expense_pembelian_obat'));
        $totalOperasional = array_sum(array_column($monthlyData, 'expense_operasional'));
        
        return [
            'months' => $monthlyData,
            'totals' => [
                'income' => array_sum(array_column($monthlyData, 'income')),
                'expense' => array_sum(array_column($monthlyData, 'expense')),
                'profit' => array_sum(array_column($monthlyData, 'profit')),
                'expense_pembelian_obat' => $totalPembelianObat,
                'expense_operasional' => $totalOperasional,
            ]
        ];
    }
    
    /**
     * Get breakdown detail menggunakan View v_laporan_keuangan
     */
    private function getBreakdownDetail($bulan, $tahun)
    {
        // Get data from view
        $data = DB::table('v_laporan_keuangan')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->get();
        
        // Group by jenis transaksi dan metode pembayaran
        $income = $data->where('jenis_transaksi', 'pemasukan');
        $expense = $data->where('jenis_transaksi', 'pengeluaran');
        
        return [
            'income' => [
                'total' => $income->sum('jumlah'),
                'by_method' => [
                    'cash' => $income->where('metode_pembayaran', 'cash')->sum('jumlah'),
                    'transfer' => $income->where('metode_pembayaran', 'transfer')->sum('jumlah'),
                    'ewallet' => $income->where('metode_pembayaran', 'ewallet')->sum('jumlah'),
                ],
                'count' => $income->count()
            ],
            'expense' => [
                'total' => $expense->sum('jumlah'),
                'pembelian_obat' => $expense->whereNotNull('id_pembelian_obat')->sum('jumlah'),
                'operasional' => $expense->whereNull('id_pembelian_obat')->sum('jumlah'),
                'by_method' => [
                    'cash' => $expense->where('metode_pembayaran', 'cash')->sum('jumlah'),
                    'transfer' => $expense->where('metode_pembayaran', 'transfer')->sum('jumlah'),
                    'ewallet' => $expense->where('metode_pembayaran', 'ewallet')->sum('jumlah'),
                ],
                'count' => $expense->count()
            ]
        ];
    }
    
    /**
     * Export laporan keuangan tahunan ke PDF
     */
    public function export(Request $request)
    {
        try {
            $tahun = $request->get('tahun', now()->year);
            $tahun = (int) $tahun;

            // Ambil data lengkap seperti di index
            $monthlyData = $this->getMonthlyData($tahun);

            // Tambahkan nama bulan untuk tampilan
            foreach ($monthlyData['months'] as $index => $month) {
                $monthlyData['months'][$index]['month_name'] = Carbon::create($tahun, $month['month'], 1)->translatedFormat('M');
            }

            $pdf = Pdf::loadView('owner.laporan-penjualan.export-pdf', [
                'monthlyData'    => $monthlyData,
                'tahun'          => $tahun,
                'tanggal_export' => now()->format('d F Y')
            ]);

            $pdf->setPaper('a4', 'landscape');

            $filename = 'laporan_keuangan_tahunan_' . $tahun . '_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error export laporan keuangan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal export laporan: ' . $e->getMessage());
        }
    }
}