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
        'agent_user_id',
        'myfatoorah_invoice_id',
        'myfatoorah_payment_id',
        'payment_url',
        'amount',
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

    protected $casts = [
        'amount' => 'decimal:3',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'webhook_received_at' => 'datetime',
        'myfatoorah_response' => 'array',
    ];

    /**
     * Get the agency this payment belongs to
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the agent who initiated this payment
     */
    public function agent(): BelongsTo
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
