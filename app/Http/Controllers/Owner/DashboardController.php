<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Pendapatan (Keseluruhan yang sudah lunas)
        $totalPendapatan = DB::table('pembayaran')
            ->where('status_pembayaran', 'lunas')
            ->sum('total_pembayaran');

        // 2. Total Pasien (Keseluruhan yang tidak dihapus)
        $totalPasien = DB::table('pasien')
            ->whereNull('deleted_at')
            ->count();

        // 3. Total Reservasi Bulan Ini
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $totalReservasi = DB::table('reservasi')
            ->whereMonth('tanggal_reservasi', $bulanIni)
            ->whereYear('tanggal_reservasi', $tahunIni)
            ->count();

        // 4. Total Treatment (Semua treatment aktif)
        $totalTreatment = DB::table('treatment')
            ->whereNull('deleted_at')
            ->count();

        // 5. Patient Status (dari VIEW)
        $patientStatus = DB::table('v_patient_status')
            ->limit(20) // Ambil 20 terbaru
            ->get();

        // 6. Jadwal Treatment Hari Ini (default)
        $tanggalDipilih = Carbon::today()->format('Y-m-d');
        
        $jadwalTreatment = DB::table('v_jadwal_treatment_harian')
            ->where('tanggal_reservasi', $tanggalDipilih)
            ->get();

        // 7. Most Popular Treatments (Bulan Ini)
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $popularTreatments = DB::table('reservasi as r')
            ->join('detail_reservasi as dr', 'r.id_reservasi', '=', 'dr.id_reservasi')
            ->join('treatment as t', 'dr.id_treatment', '=', 't.id_treatment')
            ->select(
                't.id_treatment',
                't.nama_treatment',
                DB::raw('COUNT(dr.id_detail) as total_reservasi')
            )
            ->whereMonth('r.tanggal_reservasi', $bulanIni)
            ->whereYear('r.tanggal_reservasi', $tahunIni)
            ->whereNull('dr.deleted_at')
            ->whereNull('t.deleted_at')
            ->groupBy('t.id_treatment', 't.nama_treatment')
            ->orderBy('total_reservasi', 'desc')
            ->limit(5) // Ambil top 5 treatment
            ->get();

        // Get current month name untuk display
        $currentMonthName = Carbon::now()->translatedFormat('F Y');

        return view('owner.dashboard', compact(
            'totalPendapatan',
            'totalPasien',
            'totalReservasi',
            'totalTreatment',
            'patientStatus',
            'jadwalTreatment',
            'tanggalDipilih',
            'popularTreatments',
            'currentMonthName'
        ));
    }

    /**
     * Get jadwal treatment by date (untuk AJAX)
     */
    public function getJadwalByDate(Request $request)
    {
        $tanggal = $request->input('tanggal');
        
        // Validasi format tanggal
        if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid'
            ], 400);
        }
        
        $jadwal = DB::table('v_jadwal_treatment_harian')
            ->where('tanggal_reservasi', $tanggal)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $jadwal,
            'tanggal' => $tanggal
        ]);
    }
}