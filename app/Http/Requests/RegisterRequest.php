<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Data Pribadi
            'nik' => 'required|string|min:16|max:20|regex:/^[0-9]*$/',
            'name' => 'required|string|max:255|trim',
            'telp' => 'required|string|max:20|regex:/^[0-9+\-\s()]*$/|unique:users,telp',
            'gender' => 'required|string|in:laki-laki,perempuan,Laki-laki,Perempuan',
            'alamat' => 'required|string|max:500',

            // Alamat Wilayah
            'id_provinsi' => 'required|integer|exists:provinces,id',
            'id_kota' => 'required|integer|exists:regencies,id',
            'id_kecamatan' => 'required|integer|exists:districts,id',
            'id_desa' => 'required|integer|exists:villages,id',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',

            // Akun
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)->mixedCase()->numbers()],
            'confirmPassword' => 'required|string|same:password',

            // Opsional
            'komunitas' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Data Pribadi
            'nik.required' => 'NIK wajib diisi.',
            'nik.string' => 'NIK harus berupa teks.',
            'nik.min' => 'NIK harus minimal :min digit.',
            'nik.max' => 'NIK tidak boleh lebih dari :max karakter.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',

            'name.required' => 'Nama Lengkap wajib diisi.',
            'name.string' => 'Nama Lengkap harus berupa teks.',
            'name.max' => 'Nama Lengkap tidak boleh lebih dari :max karakter.',
            'name.trim' => 'Nama Lengkap tidak boleh memiliki spasi ekstra di awal atau akhir.',

            'telp.required' => 'Nomor Telepon wajib diisi.',
            'telp.string' => 'Nomor Telepon harus berupa teks.',
            'telp.max' => 'Nomor Telepon tidak boleh lebih dari :max karakter.',
            'telp.regex' => 'Format Nomor Telepon tidak valid.',
            'telp.unique' => 'Nomor Telepon sudah terdaftar.',

            'gender.required' => 'Jenis Kelamin wajib dipilih.',
            'gender.string' => 'Jenis Kelamin harus berupa teks.',
            'gender.in' => 'Jenis Kelamin tidak valid.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat tidak boleh lebih dari :max karakter.',

            // Alamat Wilayah
            'id_provinsi.required' => 'Provinsi wajib dipilih.',
            'id_provinsi.integer' => 'Provinsi tidak valid.',
            'id_provinsi.exists' => 'Provinsi yang dipilih tidak ditemukan.',

            'id_kota.required' => 'Kota/Kabupaten wajib dipilih.',
            'id_kota.integer' => 'Kota/Kabupaten tidak valid.',
            'id_kota.exists' => 'Kota/Kabupaten yang dipilih tidak ditemukan.',

            'id_kecamatan.required' => 'Kecamatan wajib dipilih.',
            'id_kecamatan.integer' => 'Kecamatan tidak valid.',
            'id_kecamatan.exists' => 'Kecamatan yang dipilih tidak ditemukan.',

            'id_desa.required' => 'Desa/Kelurahan wajib dipilih.',
            'id_desa.integer' => 'Desa/Kelurahan tidak valid.',
            'id_desa.exists' => 'Desa/Kelurahan yang dipilih tidak ditemukan.',

            'rt.required' => 'RT wajib diisi.',
            'rt.string' => 'RT harus berupa teks.',
            'rt.max' => 'RT tidak boleh lebih dari :max karakter.',

            'rw.required' => 'RW wajib diisi.',
            'rw.string' => 'RW harus berupa teks.',
            'rw.max' => 'RW tidak boleh lebih dari :max karakter.',

            // Akun
            'email.required' => 'Alamat Email wajib diisi.',
            'email.string' => 'Alamat Email harus berupa teks.',
            'email.email' => 'Format Alamat Email tidak valid.',
            'email.max' => 'Alamat Email tidak boleh lebih dari :max karakter.',
            'email.unique' => 'Alamat Email sudah terdaftar.',

            'password.required' => 'Kata Sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi Kata Sandi tidak cocok.',
            'password.min' => 'Kata Sandi minimal :min karakter.',
            'password.mixed' => 'Kata Sandi harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Kata Sandi harus mengandung angka.',

            'confirmPassword.required' => 'Konfirmasi Kata Sandi wajib diisi.',
            'confirmPassword.string' => 'Konfirmasi Kata Sandi harus berupa teks.',
            'confirmPassword.same' => 'Konfirmasi Kata Sandi tidak cocok dengan Kata Sandi.',

            // Opsional
            'komunitas.string' => 'Komunitas harus berupa teks.',
            'komunitas.max' => 'Komunitas tidak boleh lebih dari :max karakter.',
        ];
    }
}
