<?php

namespace Tests\Unit;

use App\Services\QrisService;
use Tests\TestCase;

class QrisServiceTest extends TestCase
{
    private QrisService $qrisService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->qrisService = new QrisService();
    }

    public function test_can_validate_standard_qris(): void
    {
        $validQris = '00020101021126580014ID.LINKAJA.WWW0118936009110011003445021000000000000303UME51440014ID.CO.TELKOM.WWW02150000000000000000303UME5204581253033605802ID5915WARUNG KOPI JAYA6007JAKARTA61051234062070703A016304E85C';
        $invalidQris = 'NOT_A_QRIS_STRING';

        $this->assertTrue($this->qrisService->isValidQris($validQris));
        $this->assertFalse($this->qrisService->isValidQris($invalidQris));
    }

    public function test_can_parse_merchant_info(): void
    {
        $sample = '0002010102115802ID5923PT HUMORA MEDIA KREATIF6007JAKARTA6304A1B2';
        $info = $this->qrisService->parseMerchantInfo($sample);

        $this->assertEquals('PT HUMORA MEDIA KREATIF', $info['merchant_name']);
        $this->assertEquals('JAKARTA', $info['merchant_city']);
    }

    public function test_can_convert_static_qris_to_dynamic_with_exact_amount(): void
    {
        $static = '00020101021153033605802ID5923PT HUMORA MEDIA KREATIF6007JAKARTA63040000';
        $amount = 15000;

        $dynamic = $this->qrisService->makeDynamic($static, $amount);

        // Must change 010211 to 010212 (dynamic)
        $this->assertStringContainsString('000201010212', $dynamic);

        // Must contain Tag 54 with length 05 and value 15000 (540515000)
        $this->assertStringContainsString('540515000', $dynamic);

        // Must contain Tag 5802ID
        $this->assertStringContainsString('5802ID', $dynamic);

        // Must have valid CRC checksum at the end (6304 + 4 hex chars)
        $this->assertMatchesRegularExpression('/6304[A-F0-9]{4}$/', $dynamic);

        // Verify the CRC matches the payload
        $crcPos = strrpos($dynamic, '6304');
        $expectedCrc = $this->qrisService->calculateCrc16(substr($dynamic, 0, $crcPos + 4));
        $actualCrc = substr($dynamic, $crcPos + 4);
        $this->assertEquals($expectedCrc, $actualCrc);
    }
}
