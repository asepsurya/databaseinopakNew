<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
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
            'email.required' => 'Alamat Email wajib diisi.',
            'email.string' => 'Alamat Email harus berupa teks.',
            'email.email' => 'Format Alamat Email tidak valid.',
            'email.max' => 'Alamat Email tidak boleh lebih dari :max karakter.',

            'password.required' => 'Kata Sandi wajib diisi.',
            'password.string' => 'Kata Sandi harus berupa teks.',
            'password.min' => 'Kata Sandi minimal :min karakter.',
        ];
    }
}
