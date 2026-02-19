<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'NamaProjek' => 'required|string|max:255|trim',
            'keterangan' => 'nullable|string|max:1000',
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
            'NamaProjek.required' => 'Nama Proyek wajib diisi.',
            'NamaProjek.string' => 'Nama Proyek harus berupa teks.',
            'NamaProjek.max' => 'Nama Proyek tidak boleh lebih dari :max karakter.',
            'NamaProjek.trim' => 'Nama Proyek tidak boleh memiliki spasi ekstra di awal atau akhir.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Keterangan tidak boleh lebih dari :max karakter.',
        ];
    }
}
