<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Pasien;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id_user;
        
        // Cari pasien yang terkait dengan user ini
        $pasien = Pasien::where('id_user', $userId)->first();
        
        return [
            // User fields
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($userId, 'id_user'),
            ],
            'no_telepon' => [
                'required',
                'string',
                'regex:/^(\+62|0)[0-9]{9,14}$/',
                // Unique hanya di tabel pasien (karena users tidak punya kolom no_telepon)
                Rule::unique(Pasien::class, 'no_telepon')
                    ->ignore($pasien?->id_pasien, 'id_pasien'),
            ],
            
            // Pasien fields
            'nama_depan' => ['required', 'string', 'max:255'],
            'nama_belakang' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'alamat' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'no_telepon.regex' => 'Nomor telepon harus menggunakan format Indonesia (08xxx atau +62xxx)',
            'no_telepon.unique' => 'Nomor telepon sudah digunakan oleh akun lain',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan)',
        ];
    }
}