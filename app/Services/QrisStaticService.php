<?php

namespace App\Services;

class QrisStaticService
{
    public function generate(string $payload, float $amount): string
    {
        $payload = trim($payload);

        if (str_contains($payload, '6304')) {
            $payload = substr($payload, 0, strrpos($payload, '6304'));
        }

        $payload = preg_replace('/010211/', '010212', $payload, 1);

        $amountFormatted = number_format($amount, 0, '', '');
        $amountTag = '54' . str_pad(strlen($amountFormatted), 2, '0', STR_PAD_LEFT) . $amountFormatted;

        if (preg_match('/54\d{2}\d+/', $payload)) {
            $payload = preg_replace('/54\d{2}\d+/', $amountTag, $payload);
        } else {
            $payload .= $amountTag;
        }

        $payload .= '6304';

        $crc = strtoupper($this->crc16($payload));

        return $payload . $crc;
    }

    protected function crc16(string $str): string
    {
        $crc = 0xFFFF;

        for ($c = 0; $c < strlen($str); $c++) {
            $crc ^= ord($str[$c]) << 8;

            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }

                $crc &= 0xFFFF;
            }
        }

        return str_pad(dechex($crc), 4, '0', STR_PAD_LEFT);
    }
}
