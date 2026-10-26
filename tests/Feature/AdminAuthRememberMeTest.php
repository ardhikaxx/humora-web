<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    Admin::where('email', 'remember_admin@example.com')->delete();
    Admin::where('email', 'another_admin@example.com')->delete();
});

test('admin login with remember me queues remembered email and remember flag cookies', function () {
    $admin = Admin::create([
        'name' => 'Remember Admin',
        'email' => 'remember_admin@example.com',
        'password' => Hash::make('secret123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.login.submit'), [
        'email' => 'remember_admin@example.com',
        'password' => 'secret123',
        'remember' => '1',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $response->assertCookie('remembered_admin_email', 'remember_admin@example.com');
    $response->assertCookie('remembered_admin_remember', '1');
});

test('admin login without remember me clears remembered cookies', function () {
    $admin = Admin::create([
        'name' => 'Another Admin',
        'email' => 'another_admin@example.com',
        'password' => Hash::make('secret123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.login.submit'), [
        'email' => 'another_admin@example.com',
        'password' => 'secret123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $response->assertCookieExpired('remembered_admin_email');
    $response->assertCookieExpired('remembered_admin_remember');
});

test('after admin logs out, login page displays remembered email and checked checkbox', function () {
    $admin = Admin::create([
        'name' => 'Remember Admin',
        'email' => 'remember_admin@example.com',
        'password' => Hash::make('secret123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    // 1. Login with remember me
    $loginResponse = $this->post(route('admin.login.submit'), [
        'email' => 'remember_admin@example.com',
        'password' => 'secret123',
        'remember' => '1',
    ]);

    $loginResponse->assertRedirect(route('admin.dashboard'));

    // 2. Perform logout
    $logoutResponse = $this->actingAs($admin, 'admin')->post(route('admin.logout'));
    $logoutResponse->assertRedirect(route('admin.login'));

    // 3. Visit login page carrying the remembered cookies
    $pageResponse = $this->withCookies([
        'remembered_admin_email' => 'remember_admin@example.com',
        'remembered_admin_remember' => '1',
    ])->get(route('admin.login'));

    $pageResponse->assertStatus(200);
    $pageResponse->assertSee('value="remember_admin@example.com"', false);
    $pageResponse->assertSee('checked', false);
    $pageResponse->assertSee('Ingat Saya di Perangkat Ini');
});

test('login page without remembered cookie has blank email and unchecked remember checkbox', function () {
    $response = $this->get(route('admin.login'));

    $response->assertStatus(200);
    $response->assertSee('value=""', false);
    $response->assertDontSee('checked style="accent-color: #43135C;', false);
    $response->assertSee('Ingat Saya di Perangkat Ini');
});
