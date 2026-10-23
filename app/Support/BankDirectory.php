<?php

namespace App\Support;

class BankDirectory
{
    /**
     * Master list of Indonesian conventional, digital, and syariah banks.
     *
     * @return array<int, array{
     *     code: string,
     *     name: string,
     *     short_name: string,
     *     category: string,
     *     logo: string,
     *     pattern: array<int>,
     *     max_digits: int,
     *     placeholder: string
     * }>
     */
    public static function all(): array
    {
        return [
            // Bank Konvensional
            [
                'code' => 'bca',
                'name' => 'Bank Central Asia (BCA)',
                'short_name' => 'BCA',
                'category' => 'Bank Konvensional',
                'logo' => 'bca.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '2103-039-177',
            ],
            [
                'code' => 'mandiri',
                'name' => 'Bank Mandiri',
                'short_name' => 'Mandiri',
                'category' => 'Bank Konvensional',
                'logo' => 'mandiri.png',
                'pattern' => [3, 2, 7, 1],
                'max_digits' => 13,
                'placeholder' => '137-00-123456-7',
            ],
            [
                'code' => 'bri',
                'name' => 'Bank Rakyat Indonesia (BRI)',
                'short_name' => 'BRI',
                'category' => 'Bank Konvensional',
                'logo' => 'bri.png',
                'pattern' => [4, 2, 6, 2, 1],
                'max_digits' => 15,
                'placeholder' => '0206-01-009988-50-2',
            ],
            [
                'code' => 'bni',
                'name' => 'Bank Negara Indonesia (BNI)',
                'short_name' => 'BNI',
                'category' => 'Bank Konvensional',
                'logo' => 'bni.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '0123-456-789',
            ],
            [
                'code' => 'cimb',
                'name' => 'CIMB Niaga',
                'short_name' => 'CIMB Niaga',
                'category' => 'Bank Konvensional',
                'logo' => 'cimb.png',
                'pattern' => [3, 2, 5, 2, 1],
                'max_digits' => 13,
                'placeholder' => '700-12-34567-89-0',
            ],
            [
                'code' => 'permata',
                'name' => 'Bank Permata',
                'short_name' => 'Permata',
                'category' => 'Bank Konvensional',
                'logo' => 'permata.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '1234-567-890',
            ],
            [
                'code' => 'danamon',
                'name' => 'Bank Danamon',
                'short_name' => 'Danamon',
                'category' => 'Bank Konvensional',
                'logo' => 'danamon.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '3561-234-567',
            ],
            [
                'code' => 'btn',
                'name' => 'Bank Tabungan Negara (BTN)',
                'short_name' => 'BTN',
                'category' => 'Bank Konvensional',
                'logo' => 'btn.png',
                'pattern' => [5, 2, 2, 6, 1],
                'max_digits' => 16,
                'placeholder' => '00012-01-50-001234-5',
            ],
            [
                'code' => 'panin',
                'name' => 'Bank Panin',
                'short_name' => 'Panin',
                'category' => 'Bank Konvensional',
                'logo' => 'panin.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '1234-567-890',
            ],
            [
                'code' => 'ocbc',
                'name' => 'Bank OCBC NISP',
                'short_name' => 'OCBC',
                'category' => 'Bank Konvensional',
                'logo' => 'ocbc.png',
                'pattern' => [3, 4, 5],
                'max_digits' => 12,
                'placeholder' => '001-1234-56789',
            ],
            [
                'code' => 'maybank',
                'name' => 'Bank Maybank Indonesia',
                'short_name' => 'Maybank',
                'category' => 'Bank Konvensional',
                'logo' => 'maybank.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '1234-567-890',
            ],
            [
                'code' => 'mega',
                'name' => 'Bank Mega',
                'short_name' => 'Mega',
                'category' => 'Bank Konvensional',
                'logo' => 'mega.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '0123-456-789',
            ],
            [
                'code' => 'sinarmas',
                'name' => 'Bank Sinarmas',
                'short_name' => 'Sinarmas',
                'category' => 'Bank Konvensional',
                'logo' => 'sinarmas.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '0012-345-678',
            ],

            // Bank Digital
            [
                'code' => 'jago',
                'name' => 'Bank Jago',
                'short_name' => 'Jago',
                'category' => 'Bank Digital',
                'logo' => 'jago.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '1023-4567-8901',
            ],
            [
                'code' => 'seabank',
                'name' => 'SeaBank Indonesia',
                'short_name' => 'SeaBank',
                'category' => 'Bank Digital',
                'logo' => 'seabank.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '9012-3456-7890',
            ],
            [
                'code' => 'blu',
                'name' => 'Blu by BCA Digital',
                'short_name' => 'Blu',
                'category' => 'Bank Digital',
                'logo' => 'blu.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '0012-3456-7890',
            ],
            [
                'code' => 'jenius',
                'name' => 'Jenius (Bank BTPN)',
                'short_name' => 'Jenius',
                'category' => 'Bank Digital',
                'logo' => 'jenius.png',
                'pattern' => [3, 4, 4],
                'max_digits' => 11,
                'placeholder' => '900-1234-5678',
            ],
            [
                'code' => 'neobank',
                'name' => 'Neobank (Bank Neo Commerce)',
                'short_name' => 'Neobank',
                'category' => 'Bank Digital',
                'logo' => 'neobank.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '1234-5678-9012',
            ],
            [
                'code' => 'allo',
                'name' => 'Allo Bank',
                'short_name' => 'Allo Bank',
                'category' => 'Bank Digital',
                'logo' => 'allo.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '1000-2345-6789',
            ],
            [
                'code' => 'linebank',
                'name' => 'Line Bank (KEB Hana)',
                'short_name' => 'Line Bank',
                'category' => 'Bank Digital',
                'logo' => 'linebank.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '1234-567-890',
            ],
            [
                'code' => 'superbank',
                'name' => 'Superbank',
                'short_name' => 'Superbank',
                'category' => 'Bank Digital',
                'logo' => 'superbank.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '1000-1234-5678',
            ],
            [
                'code' => 'digibank',
                'name' => 'Digibank (DBS)',
                'short_name' => 'Digibank',
                'category' => 'Bank Digital',
                'logo' => 'digibank.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '1234-567-890',
            ],

            // Bank Syariah
            [
                'code' => 'bsi',
                'name' => 'Bank Syariah Indonesia (BSI)',
                'short_name' => 'BSI',
                'category' => 'Bank Syariah',
                'logo' => 'bsi.png',
                'pattern' => [4, 3, 3],
                'max_digits' => 10,
                'placeholder' => '7123-456-789',
            ],
            [
                'code' => 'aladin',
                'name' => 'Bank Aladin Syariah',
                'short_name' => 'Aladin',
                'category' => 'Bank Syariah',
                'logo' => 'aladin.png',
                'pattern' => [4, 4, 4],
                'max_digits' => 12,
                'placeholder' => '1000-2345-6789',
            ],
        ];
    }

    /**
     * Group banks by category for optgroup rendering.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function grouped(): array
    {
        $grouped = [];
        foreach (self::all() as $bank) {
            $cat = $bank['category'];
            if (!isset($grouped[$cat])) {
                $grouped[$cat] = [];
            }
            $grouped[$cat][] = $bank;
        }
        return $grouped;
    }

    /**
     * Find bank metadata by code or name (fuzzy match).
     *
     * @param string|null $val
     * @return array<string, mixed>|null
     */
    public static function findByCodeOrName(?string $val): ?array
    {
        if (empty($val)) {
            return null;
        }

        $needle = strtolower(trim($val));

        // Exact match by code
        foreach (self::all() as $bank) {
            if (strtolower($bank['code']) === $needle) {
                return $bank;
            }
        }

        // Exact match by full name or short name
        foreach (self::all() as $bank) {
            if (strtolower($bank['name']) === $needle || strtolower($bank['short_name']) === $needle) {
                return $bank;
            }
        }

        // Substring match
        foreach (self::all() as $bank) {
            if (str_contains(strtolower($bank['name']), $needle) || str_contains($needle, strtolower($bank['short_name']))) {
                return $bank;
            }
        }

        return null;
    }

    /**
     * Get logo URL for a bank code or name.
     */
    public static function getLogoUrl(?string $val): ?string
    {
        $bank = self::findByCodeOrName($val);
        if ($bank && !empty($bank['logo'])) {
            return asset('images/banks/' . $bank['logo']);
        }
        return null;
    }
}
