<?php

namespace App\Helpers;

class CookieHelper
{
    public static function makeRefreshToken(string $token)
    {
        $isProduction = app()->environment('production');

        return cookie(
            'refreshToken',
            $token,
            60 * 24 * 7, // 7 hari
            '/',
            null,
            true, // secure hanya saat production
            true, // httpOnly
            false, // raw
            'None',
        );
    }

    public static function forgetRefreshToken()
    {
        $isProduction = app()->environment('production');

        return cookie(
            'refreshToken',
            null, // value kosong
            -1, // expired
            '/',
            null,
            true, // secure
            true, // httpOnly
            false,
            'None',
        );
    }
}
