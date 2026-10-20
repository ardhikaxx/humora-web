<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'initials',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Generate initials from name (e.g. Admin Humora -> AH)
     */
    public function getInitialsAttribute(): string
    {
        $name = trim((string) ($this->name ?? ''));
        if (empty($name)) {
            return 'AD';
        }

        $parts = preg_split('/\s+/', $name);
        if (count($parts) >= 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1));
        }

        return strtoupper(mb_substr($parts[0], 0, min(2, mb_strlen($parts[0]))));
    }

    public function isAdmin(): bool
    {
        return true;
    }

    public function isSuperAdmin(): bool
    {
        return true;
    }

    public function isContentAdmin(): bool
    {
        return true;
    }

    public function isFinanceAdmin(): bool
    {
        return true;
    }

    public function canManageAdmins(): bool
    {
        return true;
    }

    public function canManageContent(): bool
    {
        return true;
    }

    public function canManageFinances(): bool
    {
        return true;
    }

    /**
     * Helper to verify if an admin is the last active admin
     */
    public static function isLastActiveAdmin(int $adminId): bool
    {
        $count = static::where('is_active', true)
            ->where('id', '!=', $adminId)
            ->count();

        return $count === 0;
    }

    public static function isLastActiveSuperAdmin(int $adminId): bool
    {
        return static::isLastActiveAdmin($adminId);
    }

    public function verifiedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'verified_by');
    }
}
