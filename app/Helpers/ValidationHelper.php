<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
    public static function register($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'passwordConfirmation' => 'required|same:password',
        ], [
            'name.min' => 'Nama minimal harus 3 karakter!',
            'name.max' => 'Nama maksimal 50 karakter!',
            'name.required' => 'Nama tidak boleh kosong!',
            'phone.required' => 'Nomor telepon tidak boleh kosong!',
            'phone.min' => 'Nomor telepon minimal harus 10 karakter!',
            'phone.max' => 'Nomor telepon maksimal 15 karakter!',
            'email.email' => 'Format email tidak valid!',
            'email.required' => 'Email tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
            'password.min' => 'Password minimal harus 8 karakter!',
            'password.regex' => 'Password harus mengandung huruf, angka, dan karakter spesial!',
            'passwordConfirmation.required' => 'Konfirmasi password tidak boleh kosong!',
            'passwordConfirmation.same' => 'Konfirmasi password tidak sesuai!',
        ]);
    }

    public static function login($data)
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ], [
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format email tidak valid!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);
    }

    public static function updateUser($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|min:3|max:50',
            'phone' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'email' => 'required|email',
            'instagram' => 'required|string|min:3|max:50',
        ], [
            'name.min' => 'Nama minimal harus 3 karakter!',
            'name.max' => 'Nama maksimal 50 karakter!',
            'name.required' => 'Nama tidak boleh kosong!',
            'phone.required' => 'Nomor telepon tidak boleh kosong!',
            'phone.min' => 'Nomor telepon minimal harus 10 karakter!',
            'phone.max' => 'Nomor telepon maksimal 15 karakter!',
            'email.email' => 'Format email tidak valid!',
            'email.required' => 'Email tidak boleh kosong!',
            'instagram.required' => 'Instagram tidak boleh kosong!',
            'instagram.min' => 'Instagram minimal harus 3 karakter!',
            'instagram.max' => 'Instagram maksimal 50 karakter!',
        ]);
    }
}
