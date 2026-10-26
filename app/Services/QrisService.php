<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Zxing\QrReader;

class QrisService
{
    /**
     * Decode a QR code image to its raw string.
     */
    public function decodeImage(string $filePath): ?string
    {
        if (! file_exists($filePath)) {
            return null;
        }

        try {
            $qrcode = new QrReader($filePath);
            $text = $qrcode->text();
            $trimmed = trim((string) $text);

            return ! empty($trimmed) ? $trimmed : null;
        } catch (\Throwable $e) {
            Log::warning('QRIS decode exception: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Validate whether a string is a valid QRIS payload.
     */
    public function isValidQris(string $payload): bool
    {
        $payload = trim($payload);
        // QRIS must start with 000201 (Payload format indicator)
        if (! str_starts_with($payload, '000201')) {
            return false;
        }

        // Must contain 5802ID (Country code Indonesia) and 6304 (CRC tag)
        if (! str_contains($payload, '5802ID') || ! str_contains($payload, '6304')) {
            return false;
        }

        return true;
    }

    /**
     * Parse merchant details from QRIS TLV tags.
     */
    public function parseMerchantInfo(string $payload): array
    {
        $payload = trim($payload);
        $tags = $this->parseTlv($payload);

        $merchantName = $tags['59'] ?? null;
        $merchantCity = $tags['60'] ?? null;

        return [
            'merchant_name' => $merchantName ?: 'PT Global Mobile Content',
            'merchant_city' => $merchantCity ?: 'JAKARTA',
        ];
    }

    /**
     * Parse root TLV tags from EMVCo payload.
     */
    public function parseTlv(string $payload): array
    {
        $tags = [];
        $len = strlen($payload);
        $offset = 0;

        while ($offset + 4 <= $len) {
            $tag = substr($payload, $offset, 2);
            $length = (int) substr($payload, $offset + 2, 2);
            $offset += 4;

            if ($offset + $length > $len) {
                break;
            }

            $value = substr($payload, $offset, $length);
            $tags[$tag] = $value;
            $offset += $length;

            if ($tag === '63') {
                break;
            }
        }

        return $tags;
    }

    /**
     * Convert a static QRIS payload into a dynamic QRIS payload with an exact amount.
     */
    public function makeDynamic(string $staticPayload, int|float $amount): string
    {
        $payload = trim($staticPayload);

        // Remove trailing CRC (tag 6304 + 4 hex chars)
        $crcPos = strrpos($payload, '6304');
        if ($crcPos !== false) {
            $payload = substr($payload, 0, $crcPos);
        }

        // Change Tag 01 (Point of Initiation) from 11 (static) to 12 (dynamic)
        if (str_starts_with($payload, '000201010211')) {
            $payload = '000201010212'.substr($payload, 12);
        }

        // Remove existing Tag 54 (Transaction Amount) if present
        $payload = preg_replace('/54\d{2}\d+/', '', $payload);

        // Format amount: integer without decimals for IDR
        $amountStr = (string) (int) round($amount);
        $amountLen = str_pad((string) strlen($amountStr), 2, '0', STR_PAD_LEFT);
        $tag54 = '54'.$amountLen.$amountStr;

        // Insert Tag 54 right before Tag 58 (5802ID)
        $tag58Pos = strpos($payload, '5802ID');
        if ($tag58Pos !== false) {
            $payload = substr($payload, 0, $tag58Pos).$tag54.substr($payload, $tag58Pos);
        } else {
            // Append before end if 5802ID not found
            $payload .= $tag54;
        }

        // Prepare string to calculate CRC (must include '6304')
        $dataToSign = $payload.'6304';
        $crc = $this->calculateCrc16($dataToSign);

        return $dataToSign.$crc;
    }

    /**
     * Calculate CRC-16/CCITT-FALSE (EMVCo standard: poly 0x1021, init 0xFFFF).
     */
    public function calculateCrc16(string $data): string
    {
        $crc = 0xFFFF;
        $length = strlen($data);

        for ($i = 0; $i < $length; $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
