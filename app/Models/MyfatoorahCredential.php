<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyfatoorahCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'api_key',
        'country_code',
        'is_test_mode',
        'is_active',
        'last_verified_at',
        'supported_methods',
    ];

    protected $casts = [
        'is_test_mode' => 'boolean',
        'is_active' => 'boolean',
        'last_verified_at' => 'datetime',
        'supported_methods' => 'array',
    ];

    // Note: api_key is NOT hidden - it's stored in plain text
    // Database access is already protected by authentication

    /**
     * Get the client these credentials belong to
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the base URL based on test mode
     */
    public function getBaseUrlAttribute(): string
    {
        return $this->is_test_mode
            ? 'https://apitest.myfatoorah.com'
            : 'https://api.myfatoorah.com';
    }

    /**
     * Mark credentials as verified
     */
    public function markAsVerified(): void
    {
        $this->update(['last_verified_at' => now()]);
    }

    /**
     * Check if credentials need verification (older than 24 hours)
     */
    public function needsVerification(): bool
    {
        if (is_null($this->last_verified_at)) {
            return true;
        }

        return $this->last_verified_at->diffInHours(now()) > 24;
    }
}
