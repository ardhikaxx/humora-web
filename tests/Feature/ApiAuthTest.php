<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('guest can register with valid credentials', function () {
    $payload = [
        'name' => 'Budi Pratama',
        'email' => 'budi.pratama@example.com',
        'phone' => '081234567890',
        'password' => 'SecurePass123!',
        'password_confirmation' => 'SecurePass123!',
        'gender' => 'Laki-laki',
    ];

    $response = $this->postJson('/api/v1/auth/register', $payload);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email', 'tier', 'initials'],
                'token',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'budi.pratama@example.com',
        'name' => 'Budi Pratama',
        'tier' => 'free',
        'status' => 'active',
    ]);
});

test('registration fails when email is already taken', function () {
    User::create([
        'name' => 'Existing User',
        'email' => 'existing@example.com',
        'phone' => '081299999999',
        'password' => Hash::make('Secret123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Another User',
        'email' => 'existing@example.com',
        'password' => 'Secret123!',
        'password_confirmation' => 'Secret123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('user can login with valid email and password', function () {
    $user = User::create([
        'name' => 'Ardhika Pradana',
        'email' => 'ardhika@example.com',
        'phone' => '081211112222',
        'password' => Hash::make('Password123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'ardhika@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email', 'initials'],
                'token',
            ],
        ]);

    expect($response->json('data.user.initials'))->toBe('AP');
});

test('login fails with wrong password', function () {
    User::create([
        'name' => 'Siti Rahma',
        'email' => 'siti@example.com',
        'password' => Hash::make('CorrectPassword123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'siti@example.com',
        'password' => 'WrongPassword!',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
        ]);
});

test('suspended or banned user cannot login', function () {
    User::create([
        'name' => 'Banned User',
        'email' => 'banned@example.com',
        'password' => Hash::make('Password123!'),
        'tier' => 'free',
        'status' => 'banned',
        'status_reason' => 'Violation of terms',
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'banned@example.com',
        'password' => 'Password123!',
    ]);

    $response->assertStatus(403);
});

test('authenticated user can access profile me endpoint', function () {
    $user = User::create([
        'name' => 'Maya Indah',
        'email' => 'maya@example.com',
        'phone' => '081277778888',
        'password' => Hash::make('Password123!'),
        'tier' => 'free',
        'status' => 'active',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->getJson('/api/v1/auth/me');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => 'maya@example.com',
                    'initials' => 'MI',
                ],
            ],
        ]);
});
