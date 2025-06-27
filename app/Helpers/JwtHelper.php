<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function generate(array $payload, string $secret, string $algo = 'HS256'): string
    {
        return JWT::encode($payload, $secret, $algo);
    }

    public static function decode(string $token, string $secret, string $algo = 'HS256'): object
    {
        return JWT::decode($token, new Key($secret, $algo));
    }
}
