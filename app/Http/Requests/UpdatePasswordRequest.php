<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
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
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'new_password_confirmation' => 'required|string|same:new_password',
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
            'current_password.required' => 'Kata Sandi Saat Ini wajib diisi.',
            'current_password.string' => 'Kata Sandi Saat Ini harus berupa teks.',

            'new_password.required' => 'Kata Sandi Baru wajib diisi.',
            'new_password.string' => 'Kata Sandi Baru harus berupa teks.',
            'new_password.confirmed' => 'Konfirmasi Kata Sandi Baru tidak cocok.',
            'new_password.min' => 'Kata Sandi Baru minimal :min karakter.',
            'new_password.mixed' => 'Kata Sandi Baru harus mengandung huruf besar dan huruf kecil.',
            'new_password.numbers' => 'Kata Sandi Baru harus mengandung angka.',
            'new_password.symbols' => 'Kata Sandi Baru harus mengandung simbol.',

            'new_password_confirmation.required' => 'Konfirmasi Kata Sandi Baru wajib diisi.',
            'new_password_confirmation.string' => 'Konfirmasi Kata Sandi Baru harus berupa teks.',
            'new_password_confirmation.same' => 'Konfirmasi Kata Sandi Baru tidak cocok dengan Kata Sandi Baru.',
        ];
    }
}
