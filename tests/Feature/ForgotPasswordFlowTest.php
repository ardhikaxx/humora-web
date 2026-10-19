<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function createTestAdmin(array $attrs = []): Admin
{
    return Admin::create(array_merge([
        'name' => 'Super Administrator',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'super_admin',
        'is_active' => true,
    ], $attrs));
}

// ==========================================
// 1. ADMIN WEBSITE FORGOT PASSWORD FLOW
// ==========================================

test('admin can access forgot password page', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
    $response->assertSee('Lupa Kata Sandi');
    $response->assertSee('Verifikasi Email');
});

test('admin forgot password fails with unregistered email', function () {
    $response = $this->from('/forgot-password')->post('/forgot-password', [
        'email' => 'nonexistent@gmail.com',
    ]);

    $response->assertRedirect('/forgot-password');
    $response->assertSessionHas('error');
    $this->assertNull(session('admin_reset_email'));
});

test('admin forgot password succeeds with valid registered email and sets session', function () {
    createTestAdmin(['email' => 'admin@gmail.com']);

    $response = $this->from('/forgot-password')->post('/forgot-password', [
        'email' => 'admin@gmail.com',
    ]);

    $response->assertRedirect(route('admin.password.reset'));
    $response->assertSessionHas('success');
    expect(session('admin_reset_email'))->toBe('admin@gmail.com');
});

test('admin cannot access reset password page without verified session', function () {
    $response = $this->get('/reset-password');

    $response->assertRedirect(route('admin.password.request'));
    $response->assertSessionHas('error');
});

test('admin can access reset password page with active verified session', function () {
    $response = $this->withSession(['admin_reset_email' => 'admin@gmail.com'])
        ->get('/reset-password');

    $response->assertStatus(200);
    $response->assertSee('Ubah Kata Sandi Baru');
    $response->assertSee('admin@gmail.com');
});

test('admin reset password fails if confirmation does not match', function () {
    createTestAdmin(['email' => 'admin@gmail.com']);

    $response = $this->withSession(['admin_reset_email' => 'admin@gmail.com'])
        ->from('/reset-password')
        ->post('/reset-password', [
            'password' => 'newpassword123',
            'password_confirmation' => 'mismatch123',
        ]);

    $response->assertSessionHasErrors(['password']);
});

test('admin reset password succeeds and updates password in database', function () {
    $admin = createTestAdmin(['email' => 'admin@gmail.com']);

    $response = $this->withSession(['admin_reset_email' => 'admin@gmail.com'])
        ->from('/reset-password')
        ->post('/reset-password', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

    $response->assertRedirect(route('admin.login'));
    $response->assertSessionHas('success');
    $this->assertNull(session('admin_reset_email'));

    $admin->refresh();
    expect(Hash::check('newpassword123', $admin->password))->toBeTrue();
});


// ==========================================
// 2. MOBILE USER REST API FORGOT PASSWORD FLOW
// ==========================================

test('mobile api verify forgot password fails with invalid email format', function () {
    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'email' => 'notanemail',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('mobile api verify forgot password fails when email is not registered', function () {
    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'email' => 'unknown.user@humora.id',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'success' => false,
            'message' => 'Alamat email tidak terdaftar dalam basis data sistem.',
        ]);
});

test('mobile api verify forgot password succeeds when user email exists', function () {
    $user = User::factory()->create([
        'email' => 'testuser.verify@humora.id',
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Email pengguna berhasil diverifikasi.',
            'data' => [
                'email' => $user->email,
            ],
        ]);
});

test('mobile api reset password fails with password mismatch', function () {
    $user = User::factory()->create([
        'email' => 'testuser.reset@humora.id',
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/v1/auth/reset-password', [
        'email' => $user->email,
        'password' => 'newsecret123',
        'password_confirmation' => 'differentsecret',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('mobile api reset password succeeds and updates password', function () {
    $user = User::factory()->create([
        'email' => 'testuser.success@humora.id',
        'password' => Hash::make('oldpassword'),
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/v1/auth/reset-password', [
        'email' => $user->email,
        'password' => 'freshpassword123',
        'password_confirmation' => 'freshpassword123',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $user->refresh();
    expect(Hash::check('freshpassword123', $user->password))->toBeTrue();
});
