<?php

namespace App\Support;

/**
 * Pertahanan lapis kedua anti injeksi skrip untuk input teks.
 * Lapis pertama tetap: validasi Laravel + escaping Blade {{ }} + purify HTML.
 */
class Sanitize
{
    /**
     * Teks polos: buang tag, normalkan spasi, batasi panjang.
     */
    public static function text(?string $value, int $max = 2000): ?string
    {
        if ($value === null) {
            return null;
        }
        $clean = trim(preg_replace('/\s+/u', ' ', strip_tags($value)) ?? '');

        return mb_substr($clean, 0, $max) ?: null;
    }

    /**
     * Nama orang/judul pendek.
     */
    public static function name(?string $value, int $max = 255): ?string
    {
        return static::text($value, $max);
    }

    /**
     * Nomor telepon/WA: hanya digit dan awalan +.
     */
    public static function phone(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $digits = preg_replace('/[^0-9]/', '', $value) ?? '';
        if ($digits === '') {
            return null;
        }

        return str_starts_with(trim($value), '+') ? '+'.$digits : $digits;
    }
}
