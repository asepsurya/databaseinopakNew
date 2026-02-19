<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $userId = auth()->id();

        return [
            'nama' => 'required|string|max:255|trim',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]*$/',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
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
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari :max karakter.',
            'nama.trim' => 'Nama tidak boleh memiliki spasi ekstra di awal atau akhir.',

            'email.required' => 'Alamat Email wajib diisi.',
            'email.string' => 'Alamat Email harus berupa teks.',
            'email.email' => 'Format Alamat Email tidak valid.',
            'email.max' => 'Alamat Email tidak boleh lebih dari :max karakter.',
            'email.unique' => 'Alamat Email sudah digunakan oleh pengguna lain.',

            'phone.string' => 'Nomor Telepon harus berupa teks.',
            'phone.max' => 'Nomor Telepon tidak boleh lebih dari :max karakter.',
            'phone.regex' => 'Format Nomor Telepon tidak valid.',

            'bio.string' => 'Bio harus berupa teks.',
            'bio.max' => 'Bio tidak boleh lebih dari :max karakter.',

            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat tidak boleh lebih dari :max karakter.',
        ];
    }
}
