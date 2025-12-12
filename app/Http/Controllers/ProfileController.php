<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Pasien;

class ProfileController extends Controller
{
    /**
     * Show profile with recent reservations
     */
    public function show()
    {
        $pasien = Pasien::where('id_user', Auth::id())
            ->whereNull('deleted_at')
            ->first();

        if (!$pasien) {
            return view('profile.show', [
                'recentReservations' => collect([]),
                'totalReservations' => 0,
                'completedReservations' => 0,
            ]);
        }

        // Recent 3 reservations
        $recentReservations = DB::table('v_reservasi_lengkap')
            ->where('id_pasien', $pasien->id_pasien)
            ->orderBy('tanggal_reservasi', 'desc')
            ->limit(3)
            ->get();

        // Stats
        $totalReservations = DB::table('reservasi')
            ->where('id_pasien', $pasien->id_pasien)
            ->count();

        $completedReservations = DB::table('reservasi')
            ->where('id_pasien', $pasien->id_pasien)
            ->where('status', 'completed') // ✅ Ganti dari 'done' ke 'completed'
            ->count();

        return view('profile.show', compact(
            'recentReservations',
            'totalReservations',
            'completedReservations'
        ));
    }    

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // ✅ Ambil semua data yang sudah tervalidasi
        $validated = $request->validated();

        // ✅ Update data user (hanya email dan username, karena no_telepon ada di tabel pasien)
        $userData = [
            'email' => $validated['email'],
            'username' => $validated['name'],
        ];

        // Reset email verification jika email berubah
        if ($user->email !== $validated['email']) {
            $userData['email_verified_at'] = null;
        }

        $user->update($userData);

        // ✅ Update atau create data pasien
        $pasienData = [
            'nama_depan' => $validated['nama_depan'],
            'nama_belakang' => $validated['nama_belakang'],
            'no_telepon' => $validated['no_telepon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'alamat' => $validated['alamat'],
        ];

        $pasien = Pasien::where('id_user', $user->id_user)->first();

        if ($pasien) {
            // Update existing pasien
            $pasien->update($pasienData);
        } else {
            // Create new pasien record
            $pasienData['id_user'] = $user->id_user;
            Pasien::create($pasienData);
        }

        return Redirect::route('user.profile.show')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}