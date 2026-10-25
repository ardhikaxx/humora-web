<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated administrator profile
     */
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update administrator details (Name, Email, Phone) - strictly no avatar
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profil administrator berhasil diperbarui.');
    }

    /**
     * Update administrator password with old password verification
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.',
            ]);
        }

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Password administrator berhasil diperbarui.');
    }
}
