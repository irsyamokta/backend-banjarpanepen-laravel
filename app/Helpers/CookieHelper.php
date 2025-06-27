<?php

namespace App\Helpers;

class CookieHelper
{
    public static function makeRefreshToken(string $token)
    {
        return cookie(
            'refreshToken',
            $token,
            60 * 24 * 7, // 7 hari
            '/',
            null,
            app()->environment('production'), // secure hanya saat production
            true, // httpOnly
            false, // raw
            'Lax',
        );
    }

    public static function forgetRefreshToken()
    {
        return cookie(
            'refreshToken',
            null, // value kosong
            -1, // expired
            '/',
            null,
            app()->environment('production'), // secure
            true, // httpOnly
            false,
            'Lax',
        );
    }
}
