<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_code',
        'user_id',
        'plan_id',
        'payment_method',
        'amount',
        'proof_file',
        'status',
        'verified_by',
        'verified_at',
        'reject_reason',
    ];

    protected $casts = [
        'amount' => 'float',
        'verified_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_amount',
        'proof_url',
        'payment_method_label',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp'.number_format($this->amount, 0, ',', '.');
    }

    public function getProofUrlAttribute(): ?string
    {
        if (empty($this->proof_file)) {
            return asset('images/default-img.png');
        }

        // Return secure admin file route (auth required)
        return url('/admin/transactions/proof/'.$this->proof_file);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match (strtolower((string) $this->payment_method)) {
            'bca' => 'Bank BCA',
            'mandiri' => 'Bank Mandiri',
            'qris' => 'QRIS Resmi',
            default => strtoupper((string) $this->payment_method),
        };
    }
}
