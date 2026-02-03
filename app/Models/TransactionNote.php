<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_request_id',
        'created_by_user_id',
        'note',
        'visible_to_clients',
        'note_type',
    ];

    protected $casts = [
        'visible_to_clients' => 'boolean',
    ];

    /**
     * Get the payment request this note belongs to
     */
    public function paymentRequest(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    /**
     * Get the user who created this note
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope for client-visible notes
     */
    public function scopeVisibleToClients($query)
    {
        return $query->where('visible_to_clients', true);
    }

    /**
     * Scope for internal notes only
     */
    public function scopeInternal($query)
    {
        return $query->where('visible_to_clients', false);
    }

    /**
     * Get note type label
     */
    public function getNoteTypeLabelAttribute(): string
    {
        return match($this->note_type) {
            'general' => 'General Note',
            'status_update' => 'Status Update',
            'issue' => 'Issue Reported',
            'resolution' => 'Resolution',
            'internal' => 'Internal Note',
            default => ucfirst($this->note_type),
        };
    }

    /**
     * Get note type badge color
     */
    public function getNoteTypeColorAttribute(): string
    {
        return match($this->note_type) {
            'general' => 'gray',
            'status_update' => 'blue',
            'issue' => 'red',
            'resolution' => 'green',
            'internal' => 'purple',
            default => 'gray',
        };
    }
}
