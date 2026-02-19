<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLogoRequest extends FormRequest
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
            'logo' => 'nullable|file|image|mimes:png,jpg,jpeg,svg,ico,gif,webp|max:2048',
            'name' => 'nullable|string|max:255',
            'width' => 'nullable|integer|min:16|max:500',
            'height' => 'nullable|integer|min:16|max:500',
            'alignment' => 'nullable|in:left,center,right',
            'position' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'custom_css' => 'nullable|string',
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
            'logo.file' => 'Logo harus berupa file.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Format Logo harus berupa: png, jpg, jpeg, svg, ico, gif, atau webp.',
            'logo.max' => 'Ukuran Logo maksimal :max KB.',

            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari :max karakter.',

            'width.integer' => 'Lebar harus berupa angka.',
            'width.min' => 'Lebar minimal :min piksel.',
            'width.max' => 'Lebar maksimal :max piksel.',

            'height.integer' => 'Tinggi harus berupa angka.',
            'height.min' => 'Tinggi minimal :min piksel.',
            'height.max' => 'Tinggi maksimal :max piksel.',

            'alignment.in' => 'Posisi tidak valid. Pilih antara kiri, tengah, atau kanan.',

            'position.string' => 'Posisi harus berupa teks.',
            'position.max' => 'Posisi tidak boleh lebih dari :max karakter.',

            'is_active.boolean' => 'Status Aktif harus berupa pilihan benar atau salah.',

            'custom_css.string' => 'CSS Kustom harus berupa teks.',
        ];
    }
}
