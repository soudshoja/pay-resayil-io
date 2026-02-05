<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'webhook_type',
        'endpoint_url',
        'secret_key',
        'headers',
        'is_active',
        'last_triggered_at',
        'trigger_count',
    ];

    protected $casts = [
        'headers' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
        'trigger_count' => 'integer',
    ];

    protected $hidden = [
        'secret_key',
    ];

    /**
     * Get the agency this webhook belongs to
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Scope for active webhooks
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('webhook_type', $type);
    }

    /**
     * Record a trigger
     */
    public function recordTrigger(): void
    {
        $this->update([
            'last_triggered_at' => now(),
            'trigger_count' => $this->trigger_count + 1,
        ]);
    }

    /**
     * Generate signature for payload
     */
    public function generateSignature(array $payload): string
    {
        return hash_hmac('sha256', json_encode($payload), $this->secret_key);
    }

    /**
     * Verify incoming signature
     */
    public function verifySignature(array $payload, string $signature): bool
    {
        $expected = $this->generateSignature($payload);
        return hash_equals($expected, $signature);
    }
}
