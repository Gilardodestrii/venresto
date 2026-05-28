<?php

namespace App\Support;

class QrisStatic
{
    public static function withAmount(string $payload, int|float $amount): string
    {
        $payload = preg_replace('/\s+/', '', trim($payload));
        $amount = (int) round((float) $amount);

        if ($amount < 1) {
            return $payload;
        }

        $payload = self::removeCrc($payload);
        $payload = self::removeTag($payload, '54');

        $tag54 = self::buildTag('54', (string) $amount);
        $insertBeforeTags = ['55', '56', '57', '58', '59', '60', '61', '62'];
        $insertPos = strlen($payload);

        foreach ($insertBeforeTags as $tag) {
            $pos = self::findTagPosition($payload, $tag);
            if ($pos !== null) {
                $insertPos = $pos;
                break;
            }
        }

        $payload = substr($payload, 0, $insertPos) . $tag54 . substr($payload, $insertPos);

        return self::appendCrc($payload);
    }

    private static function removeCrc(string $payload): string
    {
        $pos = strrpos($payload, '6304');

        if ($pos === false) {
            return $payload;
        }

        return substr($payload, 0, $pos);
    }

    private static function appendCrc(string $payload): string
    {
        $base = $payload . '6304';
        $crc = strtoupper(str_pad(dechex(self::crc16CcittFalse($base)), 4, '0', STR_PAD_LEFT));

        return $base . $crc;
    }

    private static function crc16CcittFalse(string $value): int
    {
        $crc = 0xFFFF;
        $length = strlen($value);

        for ($i = 0; $i < $length; $i++) {
            $crc ^= ord($value[$i]) << 8;

            for ($bit = 0; $bit < 8; $bit++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return $crc & 0xFFFF;
    }

    private static function buildTag(string $tag, string $value): string
    {
        return $tag . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private static function removeTag(string $payload, string $targetTag): string
    {
        $result = '';
        $offset = 0;
        $length = strlen($payload);

        while ($offset + 4 <= $length) {
            $tag = substr($payload, $offset, 2);
            $valueLength = (int) substr($payload, $offset + 2, 2);
            $chunkLength = 4 + $valueLength;

            if ($chunkLength < 4 || $offset + $chunkLength > $length) {
                $result .= substr($payload, $offset);
                break;
            }

            $chunk = substr($payload, $offset, $chunkLength);

            if ($tag !== $targetTag) {
                $result .= $chunk;
            }

            $offset += $chunkLength;
        }

        if ($offset < $length) {
            $result .= substr($payload, $offset);
        }

        return $result;
    }

    private static function findTagPosition(string $payload, string $targetTag): ?int
    {
        $offset = 0;
        $length = strlen($payload);

        while ($offset + 4 <= $length) {
            $tag = substr($payload, $offset, 2);
            $valueLength = (int) substr($payload, $offset + 2, 2);
            $chunkLength = 4 + $valueLength;

            if ($tag === $targetTag) {
                return $offset;
            }

            if ($chunkLength < 4 || $offset + $chunkLength > $length) {
                break;
            }

            $offset += $chunkLength;
        }

        return null;
    }
}
