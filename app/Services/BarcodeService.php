<?php

namespace App\Services;

class BarcodeService
{
    public static function generateEan13(int $sequenceNumber): string
    {
        $base = '200' . str_pad((string)$sequenceNumber, 9, '0', STR_PAD_LEFT);

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int)$base[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return $base . $checksum;
    }
}