<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index(Request $request): View
    {
        $query = Admin::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $admins = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Store a newly created administrator.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'admin',
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Administrator baru berhasil ditambahkan.');
    }

    /**
     * Update the specified administrator.
     */
    public function update(Request $request, Admin $admin): RedirectResponse
    {
        $currentAdmin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isActive = $request->has('is_active');

        // Prevent self-deactivation
        if ($currentAdmin->id === $admin->id && !$isActive) {
            return back()->withErrors(['is_active' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        // Prevent deactivating the last active administrator
        if (!$isActive && Admin::isLastActiveAdmin($admin->id)) {
            return back()->withErrors(['is_active' => 'Sistem harus memiliki setidaknya satu Administrator aktif.']);
        }

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->role = 'admin';
        $admin->is_active = $isActive;

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', "Data admin {$admin->name} berhasil diperbarui.");
    }

    /**
     * Remove the specified administrator.
     */
    public function destroy(Admin $admin): RedirectResponse
    {
        $currentAdmin = Auth::guard('admin')->user();

        if ($currentAdmin->id === $admin->id) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        if (Admin::isLastActiveAdmin($admin->id)) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus satu-satunya Administrator aktif di sistem.']);
        }

        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', "Admin {$name} berhasil dihapus.");
    }
}
