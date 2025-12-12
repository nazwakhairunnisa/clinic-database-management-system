<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        // 1. Reservasi Hari Ini
        $hariIni = Carbon::today()->format('Y-m-d');
        
        $reservasiHariIni = DB::table('reservasi')
            ->where('tanggal_reservasi', $hariIni)
            ->count();

        // 2. Total Pasien (Keseluruhan yang tidak dihapus)
        $totalPasien = DB::table('pasien')
            ->whereNull('deleted_at')
            ->count();

        // 3. Patient Status (Status Reservasi) - Ambil 20 terbaru
        $patientStatus = DB::table('v_patient_status')
            ->limit(20)
            ->get();

        // 4. Jadwal Treatment Hari Ini (default)
        $jadwalTreatment = DB::table('v_jadwal_treatment_harian')
            ->where('tanggal_reservasi', $hariIni)
            ->get();

        return view('admin.dashboard', compact(
            'reservasiHariIni',
            'totalPasien',
            'patientStatus',
            'jadwalTreatment'
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