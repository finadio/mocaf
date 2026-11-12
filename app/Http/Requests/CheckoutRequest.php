<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'customer_name' => 'required|string|min:3|max:100',
            'whatsapp_number' => 'required|string|min:8|max:20|regex:/^[0-9+\s\-()]+$/',
            'address' => 'required|string|min:10|max:1000',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Custom message for validation in Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_name.min' => 'Nama lengkap minimal 3 karakter.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid.',
            'address.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'address.min' => 'Alamat pengiriman terlalu singkat, mohon tuliskan alamat lengkap.',
        ];
    }
}

