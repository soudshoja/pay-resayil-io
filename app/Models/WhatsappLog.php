<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'user_id',
        'recipient',
        'message_type',
        'payload',
        'response',
        'status',
        'message_id',
        'sent_at',
        'delivered_at',
        'read_at',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Get the agency this log belongs to
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the user this log belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful messages
     */
    public function scopeSent($query)
    {
        return $query->whereIn('status', ['sent', 'delivered', 'read']);
    }

    /**
     * Scope for failed messages
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by message type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('message_type', $type);
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'sent' => 'blue',
            'delivered' => 'green',
            'read' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            default => 'gray',
        };
    }
}
