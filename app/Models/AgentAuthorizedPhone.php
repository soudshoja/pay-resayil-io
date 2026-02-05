<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentAuthorizedPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'phone_number',
        'full_name',
        'is_verified',
        'verified_at',
        'is_active',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the agent this phone belongs to
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Scope for active phones only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified phones only
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Mark phone as verified
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Find agent by phone number
     */
    public static function findAgentByPhone(string $phoneNumber): ?Agent
    {
        $normalized = self::normalizePhone($phoneNumber);

        $record = self::where('phone_number', $normalized)
            ->where('is_active', true)
            ->first();

        return $record?->agent;
    }

    /**
     * Normalize phone number to E.164 format
     */
    public static function normalizePhone(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (!str_starts_with($phoneNumber, '965') && strlen($phoneNumber) == 8) {
            $phoneNumber = '965' . $phoneNumber;
        }

        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Set phone number attribute (auto-normalize)
     */
    public function setPhoneNumberAttribute($value): void
    {
        $this->attributes['phone_number'] = self::normalizePhone($value);
    }
}
