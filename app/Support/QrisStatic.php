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
        $payload = self::setPointOfInitiationToDynamic($payload);
        $payload = self::removeTag($payload, '54');

        if (!self::findTagPosition($payload, '62') && str_contains($payload, 'ID.DANA.WWW')) {
            $payload = self::addDanaAdditionalData($payload);
        }

        $tag54 = self::buildTag('54', (string) $amount);

        $insertPos = self::findTagPosition($payload, '55')
            ?? self::findTagPosition($payload, '58')
            ?? self::findTagPosition($payload, '59')
            ?? self::findTagPosition($payload, '60')
            ?? self::findTagPosition($payload, '61')
            ?? strlen($payload);

        $payload = substr($payload, 0, $insertPos)
            . $tag54
            . substr($payload, $insertPos);

        return self::appendCrc($payload);
    }

    private static function addDanaAdditionalData(string $payload): string
    {
        $subTag = self::extractDanaMerchantAccountId($payload);

        if (!$subTag) {
            return $payload;
        }

        $tag62 = self::buildTag('62', self::buildTag('60', $subTag));
        $insertPos = self::findTagPosition($payload, '63') ?? strlen($payload);

        return substr($payload, 0, $insertPos) . $tag62 . substr($payload, $insertPos);
    }

    private static function extractDanaMerchantAccountId(string $payload): ?string
    {
        $tag26 = self::getTagValue($payload, '26');

        if (!$tag26) {
            return null;
        }

        $subTags = self::parseTags($tag26);

        return $subTags['01'] ?? null;
    }

    private static function getTagValue(string $payload, string $targetTag): ?string
    {
        $offset = 0;
        $length = strlen($payload);

        while ($offset + 4 <= $length) {
            $tag = substr($payload, $offset, 2);
            $valueLength = (int) substr($payload, $offset + 2, 2);
            $chunkLength = 4 + $valueLength;

            if ($chunkLength < 4 || $offset + $chunkLength > $length) {
                break;
            }

            if ($tag === $targetTag) {
                return substr($payload, $offset + 4, $valueLength);
            }

            $offset += $chunkLength;
        }

        return null;
    }

    private static function setPointOfInitiationToDynamic(string $payload): string
    {
        $tags = self::parseTags($payload);

        if (($tags['01'] ?? null) === '11') {
            return self::replaceTag($payload, '01', '12');
        }

        if (!isset($tags['01'])) {
            return self::insertAfterTag($payload, '00', self::buildTag('01', '12'));
        }

        return $payload;
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

    private static function parseTags(string $payload): array
    {
        $tags = [];
        $offset = 0;
        $length = strlen($payload);

        while ($offset + 4 <= $length) {
            $tag = substr($payload, $offset, 2);
            $valueLength = (int) substr($payload, $offset + 2, 2);
            $chunkLength = 4 + $valueLength;

            if ($chunkLength < 4 || $offset + $chunkLength > $length) {
                break;
            }

            $tags[$tag] = substr($payload, $offset + 4, $valueLength);
            $offset += $chunkLength;
        }

        return $tags;
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

    private static function replaceTag(string $payload, string $targetTag, string $newValue): string
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

            if ($tag === $targetTag) {
                $result .= self::buildTag($tag, $newValue);
            } else {
                $result .= substr($payload, $offset, $chunkLength);
            }

            $offset += $chunkLength;
        }

        return $result;
    }

    private static function insertAfterTag(string $payload, string $targetTag, string $newTag): string
    {
        $offset = 0;
        $length = strlen($payload);

        while ($offset + 4 <= $length) {
            $tag = substr($payload, $offset, 2);
            $valueLength = (int) substr($payload, $offset + 2, 2);
            $chunkLength = 4 + $valueLength;

            if ($chunkLength < 4 || $offset + $chunkLength > $length) {
                break;
            }

            if ($tag === $targetTag) {
                $insertPos = $offset + $chunkLength;
                return substr($payload, 0, $insertPos) . $newTag . substr($payload, $insertPos);
            }

            $offset += $chunkLength;
        }

        return $newTag . $payload;
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
