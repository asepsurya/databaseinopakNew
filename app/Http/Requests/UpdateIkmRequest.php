<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIkmRequest extends FormRequest
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
            // Data Pemilik IKM
            'nama' => 'nullable|string|max:255|trim',
            'gender' => 'nullable|string|in:laki-laki,perempuan,Laki-laki,Perempuan',
            'alamat' => 'nullable|string|max:500',
            'id_provinsi' => 'nullable|integer|exists:provinces,id',
            'id_kota' => 'nullable|integer|exists:regencies,id',
            'id_kecamatan' => 'nullable|integer|exists:districts,id',
            'id_desa' => 'nullable|integer|exists:villages,id',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'telp' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]*$/',

            // Data Produk
            'jenisProduk' => 'nullable|string|max:255',
            'merk' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'kelebihan' => 'nullable|string|max:1000',
            'gramasi' => 'nullable|string|max:100',
            'jenisKemasan' => 'nullable|string|max:255',
            'segmentasi' => 'nullable|string|max:255',
            'harga' => 'nullable|string|max:50',
            'varian' => 'nullable|string|max:500',
            'komposisi' => 'nullable|string|max:2000',
            'redaksi' => 'nullable|string|max:2000',
            'other' => 'nullable|string|max:1000',

            // Data Perizinan
            'namaUsaha' => 'nullable|string|max:255|trim',
            'noNIB' => 'nullable|string|max:50',
            'noISO' => 'nullable|string|max:50',
            'noPIRT' => 'nullable|string|max:50',
            'noHAKI' => 'nullable|string|max:50',
            'noLayakSehat' => 'nullable|string|max:50',
            'noHalal' => 'nullable|string|max:50',
            'CPPOB' => 'nullable|string|max:50',
            'HACCP' => 'nullable|string|max:50',
            'legalitasLain' => 'nullable|string|max:500',

            // Relasi
            'id_Project' => 'nullable|integer|exists:projects,id',
            'gambar' => 'nullable|string|max:500',
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
            // Data Pemilik IKM
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari :max karakter.',
            'nama.trim' => 'Nama tidak boleh memiliki spasi ekstra di awal atau akhir.',

            'gender.string' => 'Jenis Kelamin harus berupa teks.',
            'gender.in' => 'Jenis Kelamin tidak valid.',

            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat tidak boleh lebih dari :max karakter.',

            'id_provinsi.integer' => 'Provinsi tidak valid.',
            'id_provinsi.exists' => 'Provinsi yang dipilih tidak ditemukan.',

            'id_kota.integer' => 'Kota/Kabupaten tidak valid.',
            'id_kota.exists' => 'Kota/Kabupaten yang dipilih tidak ditemukan.',

            'id_kecamatan.integer' => 'Kecamatan tidak valid.',
            'id_kecamatan.exists' => 'Kecamatan yang dipilih tidak ditemukan.',

            'id_desa.integer' => 'Desa/Kelurahan tidak valid.',
            'id_desa.exists' => 'Desa/Kelurahan yang dipilih tidak ditemukan.',

            'rt.string' => 'RT harus berupa teks.',
            'rt.max' => 'RT tidak boleh lebih dari :max karakter.',

            'rw.string' => 'RW harus berupa teks.',
            'rw.max' => 'RW tidak boleh lebih dari :max karakter.',

            'telp.string' => 'Nomor Telepon harus berupa teks.',
            'telp.max' => 'Nomor Telepon tidak boleh lebih dari :max karakter.',
            'telp.regex' => 'Format Nomor Telepon tidak valid.',

            // Data Produk
            'jenisProduk.string' => 'Jenis Produk harus berupa teks.',
            'jenisProduk.max' => 'Jenis Produk tidak boleh lebih dari :max karakter.',

            'merk.string' => 'Merk harus berupa teks.',
            'merk.max' => 'Merk tidak boleh lebih dari :max karakter.',

            'tagline.string' => 'Tagline harus berupa teks.',
            'tagline.max' => 'Tagline tidak boleh lebih dari :max karakter.',

            'kelebihan.string' => 'Keunggulan Produk harus berupa teks.',
            'kelebihan.max' => 'Keunggulan Produk tidak boleh lebih dari :max karakter.',

            'gramasi.string' => 'Gramasi harus berupa teks.',
            'gramasi.max' => 'Gramasi tidak boleh lebih dari :max karakter.',

            'jenisKemasan.string' => 'Jenis Kemasan harus berupa teks.',
            'jenisKemasan.max' => 'Jenis Kemasan tidak boleh lebih dari :max karakter.',

            'segmentasi.string' => 'Segmentasi Pasar harus berupa teks.',
            'segmentasi.max' => 'Segmentasi Pasar tidak boleh lebih dari :max karakter.',

            'harga.string' => 'Harga harus berupa teks.',
            'harga.max' => 'Harga tidak boleh lebih dari :max karakter.',

            'varian.string' => 'Varian Produk harus berupa teks.',
            'varian.max' => 'Varian Produk tidak boleh lebih dari :max karakter.',

            'komposisi.string' => 'Komposisi Produk harus berupa teks.',
            'komposisi.max' => 'Komposisi Produk tidak boleh lebih dari :max karakter.',

            'redaksi.string' => 'Redaksi harus berupa teks.',
            'redaksi.max' => 'Redaksi tidak boleh lebih dari :max karakter.',

            'other.string' => 'Lainnya harus berupa teks.',
            'other.max' => 'Lainnya tidak boleh lebih dari :max karakter.',

            // Data Perizinan
            'namaUsaha.string' => 'Nama Usaha harus berupa teks.',
            'namaUsaha.max' => 'Nama Usaha tidak boleh lebih dari :max karakter.',
            'namaUsaha.trim' => 'Nama Usaha tidak boleh memiliki spasi ekstra di awal atau akhir.',

            'noNIB.string' => 'Nomor NIB harus berupa teks.',
            'noNIB.max' => 'Nomor NIB tidak boleh lebih dari :max karakter.',

            'noISO.string' => 'Nomor ISO harus berupa teks.',
            'noISO.max' => 'Nomor ISO tidak boleh lebih dari :max karakter.',

            'noPIRT.string' => 'Nomor PIRT harus berupa teks.',
            'noPIRT.max' => 'Nomor PIRT tidak boleh lebih dari :max karakter.',

            'noHAKI.string' => 'Nomor HAKI harus berupa teks.',
            'noHAKI.max' => 'Nomor HAKI tidak boleh lebih dari :max karakter.',

            'noLayakSehat.string' => 'Nomor Surat Keterangan Layak Sehat harus berupa teks.',
            'noLayakSehat.max' => 'Nomor Surat Keterangan Layak Sehat tidak boleh lebih dari :max karakter.',

            'noHalal.string' => 'Nomor Sertifikat Halal harus berupa teks.',
            'noHalal.max' => 'Nomor Sertifikat Halal tidak boleh lebih dari :max karakter.',

            'CPPOB.string' => 'CPPOB harus berupa teks.',
            'CPPOB.max' => 'CPPOB tidak boleh lebih dari :max karakter.',

            'HACCP.string' => 'HACCP harus berupa teks.',
            'HACCP.max' => 'HACCP tidak boleh lebih dari :max karakter.',

            'legalitasLain.string' => 'Legalitas Lainnya harus berupa teks.',
            'legalitasLain.max' => 'Legalitas Lainnya tidak boleh lebih dari :max karakter.',

            // Relasi
            'id_Project.integer' => 'Proyek tidak valid.',
            'id_Project.exists' => 'Proyek yang dipilih tidak ditemukan.',

            'gambar.string' => 'Gambar harus berupa teks.',
            'gambar.max' => 'Gambar tidak boleh lebih dari :max karakter.',
        ];
    }
}
