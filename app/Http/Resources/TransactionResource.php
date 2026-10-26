<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'trx_code' => $this->trx_code,
            'plan_id' => $this->plan_id,
            'plan_name' => $this->plan ? $this->plan->name : 'Paket Langganan',
            'plan_type' => $this->plan ? ($this->plan->plan_type ?? 'weekly') : 'weekly',
            'video_quota' => $this->plan ? $this->plan->video_quota : null,
            'duration_days' => $this->plan ? $this->plan->duration_days : 7,
            'payment_method' => $this->payment_method,
            'payment_method_label' => $this->payment_method_label ?? strtoupper($this->payment_method),
            'amount' => (float) $this->amount,
            'formatted_amount' => $this->formatted_amount ?? ('Rp '.number_format($this->amount, 0, ',', '.')),
            'status' => $this->status,
            'proof_uploaded' => ! empty($this->proof_file),
            'proof_file' => $this->proof_file,
            'proof_url' => $this->proof_url,
            'reject_reason' => $this->reject_reason,
            'created_at' => $this->created_at?->toIso8601String(),
            'verified_at' => $this->verified_at?->toIso8601String(),
        ];
    }
}
