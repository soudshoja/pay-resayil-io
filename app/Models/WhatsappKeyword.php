<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'keyword',
        'action',
        'response_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the client this keyword belongs to
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope for active keywords only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find matching keyword for a message
     */
    public static function findMatch(int $clientId, string $message): ?self
    {
        $message = strtolower(trim($message));

        return self::where('client_id', $clientId)
            ->where('is_active', true)
            ->get()
            ->first(function ($keyword) use ($message) {
                return str_contains($message, strtolower($keyword->keyword));
            });
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'payment_request' => 'Generate Payment Link',
            'balance_check' => 'Check Balance',
            'status_check' => 'Check Payment Status',
            'help' => 'Send Help Message',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
