<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = Admin::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Admin Humora',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]
    );
});

test('admin management page displays admin role badge', function () {
    $response = $this->actingAs($this->admin, 'admin')->get('/admin/admins');

    $response->assertStatus(200);
    $response->assertSee('badge-role-admin', false);
    $response->assertSee('Admin');
    $response->assertDontSee('Super Admin');
    $response->assertDontSee('Content Admin');
    $response->assertDontSee('Finance Admin');
});

test('admin can store a new administrator with role admin', function () {
    $response = $this->actingAs($this->admin, 'admin')->post('/admin/admins', [
        'name' => 'Budi Pengelola',
        'email' => 'budi@humora.id',
        'phone' => '081299998888',
        'password' => 'Humora1234!',
        'password_confirmation' => 'Humora1234!',
        'is_active' => '1',
    ]);

    $response->assertRedirect('/admin/admins');
    $this->assertDatabaseHas('admins', [
        'email' => 'budi@humora.id',
        'role' => 'admin',
        'is_active' => true,
    ]);
});

test('admin can update another administrator', function () {
    $other = Admin::create([
        'name' => 'Staf Lama',
        'email' => 'staf@humora.id',
        'phone' => '081200001111',
        'password' => Hash::make('Humora1234!'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->admin, 'admin')->put("/admin/admins/{$other->id}", [
        'name' => 'Staf Baru Diperbarui',
        'email' => 'staf_baru@humora.id',
        'phone' => '081200002222',
    ]);

    $response->assertRedirect('/admin/admins');
    $this->assertDatabaseHas('admins', [
        'id' => $other->id,
        'name' => 'Staf Baru Diperbarui',
        'email' => 'staf_baru@humora.id',
        'role' => 'admin',
    ]);
});

test('admin cannot deactivate own account', function () {
    $response = $this->actingAs($this->admin, 'admin')->put("/admin/admins/{$this->admin->id}", [
        'name' => $this->admin->name,
        'email' => $this->admin->email,
        // no is_active sent -> means deactivated
    ]);

    $response->assertSessionHasErrors(['is_active']);
    $this->assertTrue($this->admin->fresh()->is_active);
});

test('admin cannot delete own account', function () {
    $response = $this->actingAs($this->admin, 'admin')->delete("/admin/admins/{$this->admin->id}");

    $response->assertSessionHasErrors(['error']);
    $this->assertDatabaseHas('admins', [
        'id' => $this->admin->id,
    ]);
});

test('admin cannot delete the last active admin', function () {
    // Delete any other admins first
    Admin::where('id', '!=', $this->admin->id)->delete();

    $response = $this->actingAs($this->admin, 'admin')->delete("/admin/admins/{$this->admin->id}");

    $response->assertSessionHasErrors(['error']);
    $this->assertDatabaseHas('admins', [
        'id' => $this->admin->id,
    ]);
});

test('admin profile page renders single admin role badge', function () {
    $response = $this->actingAs($this->admin, 'admin')->get('/admin/profile');

    $response->assertStatus(200);
    $response->assertSee('badge-role-admin', false);
    $response->assertSee('Admin');
    $response->assertDontSee('Super Admin');
    $response->assertDontSee('Content Admin');
    $response->assertDontSee('Finance Admin');
});
