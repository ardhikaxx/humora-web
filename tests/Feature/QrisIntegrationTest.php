<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrisIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SystemSetting::set('qris_enabled', 'true', 'boolean');
        SystemSetting::set('qris_raw_payload', '00020101021153033605802ID5923PT HUMORA MEDIA KREATIF6007JAKARTA63040000', 'text');
        SystemSetting::set('qris_account_name', 'PT HUMORA MEDIA KREATIF', 'text');
    }

    public function test_payment_info_includes_qris_configuration_and_status(): void
    {
        $response = $this->getJson('/api/v1/settings/payment-info');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.qris.code', 'qris')
            ->assertJsonPath('data.qris.is_enabled', true)
            ->assertJsonPath('data.qris.has_dynamic_support', true)
            ->assertJsonPath('data.qris.account_name', 'PT HUMORA MEDIA KREATIF');
    }

    public function test_can_generate_dynamic_qris_for_exact_plan_amount(): void
    {
        $response = $this->getJson('/api/v1/subscription/qris-dynamic?amount=15000');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.amount', 15000)
            ->assertJsonPath('data.account_name', 'PT HUMORA MEDIA KREATIF');

        $dynamicPayload = $response->json('data.dynamic_payload');
        $this->assertStringContainsString('000201010212', $dynamicPayload);
        $this->assertStringContainsString('540515000', $dynamicPayload);
        $this->assertStringContainsString('5802ID', $dynamicPayload);
    }

    public function test_dynamic_qris_fails_when_qris_is_disabled_by_admin(): void
    {
        SystemSetting::set('qris_enabled', 'false', 'boolean');

        $response = $this->getJson('/api/v1/subscription/qris-dynamic?amount=15000');
        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_checkout_fails_if_user_submits_qris_while_disabled_by_admin(): void
    {
        SystemSetting::set('qris_enabled', 'false', 'boolean');
        $user = User::factory()->create();
        $plan = SubscriptionPlan::firstOrCreate(['code' => 'weekly_15'], [
            'name' => 'Weekly 15',
            'plan_type' => 'weekly',
            'duration_days' => 7,
            'video_quota' => 15,
            'price' => 15000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/subscription/checkout', [
            'plan_id' => $plan->id,
            'payment_method' => 'qris',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('error_code', 'QRIS_DISABLED');
    }
}
