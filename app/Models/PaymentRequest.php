<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'client_id',
        'agent_id',
        'agent_user_id',
        'myfatoorah_invoice_id',
        'myfatoorah_payment_id',
        'payment_url',
        'amount',
        'service_fee',
        'total_amount',
        'currency',
        'customer_phone',
        'customer_name',
        'customer_email',
        'description',
        'status',
        'paid_at',
        'expires_at',
        'myfatoorah_response',
        'webhook_received_at',
        'reference_id',
        'track_id',
    ];

    /**
     * Map client_id to agency_id for backward compatibility
     */
    public function setClientIdAttribute($value): void
    {
        $this->attributes['agency_id'] = $value;
    }

    public function getClientIdAttribute()
    {
        return $this->attributes['agency_id'] ?? null;
    }

    protected $casts = [
        'amount' => 'decimal:3',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'webhook_received_at' => 'datetime',
        'myfatoorah_response' => 'array',
    ];

    /**
     * Get the agency/client this payment belongs to
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    /**
     * Alias: Get the client this payment belongs to
     */
    public function client(): BelongsTo
    {
        return $this->agency();
    }

    /**
     * Get the agent (travel agency) who initiated this payment
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Agent::class, 'agent_id');
    }

    /**
     * Get the agent user who initiated this payment (legacy)
     */
    public function agentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get transaction notes
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionNote::class, 'payment_request_id');
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(array $response = []): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'myfatoorah_response' => array_merge(
                $this->myfatoorah_response ?? [],
                $response
            ),
            'webhook_received_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(array $response = []): void
    {
        $this->update([
            'status' => 'failed',
            'myfatoorah_response' => array_merge(
                $this->myfatoorah_response ?? [],
                $response
            ),
        ]);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 3) . ' ' . $this->currency;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'paid' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            'expired' => 'gray',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
