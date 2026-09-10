<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

/**
 * Standar kata sandi tunggal untuk seluruh aplikasi:
 * min. 8 karakter, huruf, campuran besar-kecil, angka, dan simbol.
 */
class Passwords
{
    public static function rule(): Password
    {
        return Password::min(8)->letters()->mixedCase()->numbers()->symbols();
    }
}
