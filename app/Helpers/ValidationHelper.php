<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
    public static function register($data)
    {
        return Validator::make(
            $data,
            [
                'name' => 'required|string|min:3|max:50',
                'email' => 'required|email|unique:users',
                'phone' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
                'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
                'passwordConfirmation' => 'required|same:password',
            ],
            [
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
            ],
        );
    }

    public static function login($data)
    {
        return Validator::make(
            $data,
            [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ],
            [
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Format email tidak valid!',
                'password.required' => 'Password tidak boleh kosong!',
            ],
        );
    }

    public static function updateUser($data)
    {
        return Validator::make(
            $data,
            [
                'name' => 'required|string|min:3|max:50',
                'phone' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
                'email' => 'required|email',
                'instagram' => 'required|string|min:3|max:50',
            ],
            [
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
            ],
        );
    }

    public static function tour($data)
    {
        return Validator::make(
            $data,
            [
                'title' => 'required|string',
                'about' => 'required|string',
                'operational' => 'required|string',
                'location' => 'required|string',
                'start' => 'required|date_format:H:i',
                'end' => 'required|date_format:H:i',
                'facility' => 'required|string',
                'maps' => 'string',
                'price' => 'required|numeric',
            ],
            [
                'title.required' => 'Judul tidak boleh kosong!',
                'title.string' => 'Judul harus berupa string!',
                'about.required' => 'About tidak boleh kosong!',
                'about.string' => 'About harus berupa string!',
                'operational.required' => 'Operational tidak boleh kosong!',
                'operational.string' => 'Operational harus berupa string!',
                'location.required' => 'Location tidak boleh kosong!',
                'location.string' => 'Location harus berupa string!',
                'start.required' => 'Start tidak boleh kosong!',
                'start.string' => 'Start harus berupa string!',
                'end.required' => 'End tidak boleh kosong!',
                'end.string' => 'End harus berupa string!',
                'facility.required' => 'Facility tidak boleh kosong!',
                'facility.string' => 'Facility harus berupa string!',
                'price.required' => 'Price tidak boleh kosong!',
                'price.numeric' => 'Price harus berupa angka!',
            ],
        );
    }

    public static function event(array $data)
    {
        return Validator::make(
            $data,
            [
                'title' => 'required|string|min:3|max:100',
                'description' => 'required|string|min:3',
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'time' => 'required|date_format:H:i',
                'place' => 'required|string|min:3',
                'price' => 'nullable|numeric|min:0',
            ],
            [
                'title.required' => 'Judul tidak boleh kosong!',
                'title.min' => 'Judul minimal harus 3 karakter!',
                'title.max' => 'Judul maksimal 100 karakter!',

                'description.required' => 'Deskripsi tidak boleh kosong!',
                'description.min' => 'Deskripsi minimal harus 3 karakter!',

                'date.required' => 'Tanggal tidak boleh kosong!',
                'date.date' => 'Tanggal tidak valid!',
                'date.after_or_equal' => 'Tanggal harus dari hari ini atau lebih!',

                'time.required' => 'Jam mulai tidak boleh kosong!',
                'time.regex' => 'Format jam mulai harus HH:mm (contoh: 08:00)',

                'place.required' => 'Tempat tidak boleh kosong!',
                'place.min' => 'Tempat minimal harus 3 karakter!',

                'price.min' => 'Harga minimal adalah 0!',
                'price.numeric' => 'Harga harus berupa angka!',
            ],
        );
    }

    public static function package($data)
    {
        return Validator::make(
            $data,
            [
                'title' => 'required|string|min:3|max:100',
                'price' => 'required|numeric|min:10000',
                'benefit' => 'required|string|min:3',
            ],
            [
                'title.required' => 'Judul tidak boleh kosong!',
                'title.string' => 'Judul harus berupa string!',
                'title.min' => 'Judul minimal harus 3 karakter!',
                'title.max' => 'Judul maksimal 100 karakter!',

                'price.required' => 'Harga tidak boleh kosong!',
                'price.numeric' => 'Harga harus berupa angka!',
                'price.min' => 'Harga minimal adalah 10000!',

                'benefit.required' => 'Benefit tidak boleh kosong!',
                'benefit.string' => 'Benefit harus berupa string!',
                'benefit.min' => 'Benefit minimal harus 3 karakter!',
            ],
        );
    }

    public static function article($data)
    {
        return Validator::make(
            $data,
            [
                'title' => 'required|string|min:3|max:100',
                'content' => 'required|string|min:3',
                'writer' => 'required|string|min:3',
            ],
            [
                'title.required' => 'Judul tidak boleh kosong!',
                'title.string' => 'Judul harus berupa string!',
                'title.min' => 'Judul minimal harus 3 karakter!',
                'title.max' => 'Judul maksimal 100 karakter!',

                'content.required' => 'Konten tidak boleh kosong!',
                'content.string' => 'Konten harus berupa string!',
                'content.min' => 'Konten minimal harus 3 karakter!',

                'writer.required' => 'Penulis tidak boleh kosong!',
                'writer.string' => 'Penulis harus berupa string!',
                'writer.min' => 'Penulis minimal harus 3 karakter!',
            ],
        );
    }
}
