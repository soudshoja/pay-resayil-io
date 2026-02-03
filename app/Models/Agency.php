<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_name',
        'iata_number',
        'address',
        'company_email',
        'phone',
        'logo_path',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all users belonging to this agency
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the admin user for this agency
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'admin');
    }

    /**
     * Get all accountants for this agency
     */
    public function accountants(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'accountant');
    }

    /**
     * Get all agents for this agency
     */
    public function agents(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'agent');
    }

    /**
     * Get MyFatoorah credentials for this agency
     */
    public function myfatoorahCredential(): HasOne
    {
        return $this->hasOne(MyfatoorahCredential::class);
    }

    /**
     * Get all payment requests for this agency
     */
    public function paymentRequests(): HasMany
    {
        return $this->hasMany(PaymentRequest::class);
    }

    /**
     * Get all WhatsApp logs for this agency
     */
    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class);
    }

    /**
     * Get all activity logs for this agency
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get webhook configurations for this agency
     */
    public function webhookConfigs(): HasMany
    {
        return $this->hasMany(WebhookConfig::class);
    }

    /**
     * Scope for active agencies only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if agency has valid MyFatoorah credentials
     */
    public function hasValidCredentials(): bool
    {
        return $this->myfatoorahCredential &&
               $this->myfatoorahCredential->is_active &&
               !empty($this->myfatoorahCredential->api_key);
    }

    /**
     * Get formatted display name with IATA
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->agency_name} (IATA: {$this->iata_number})";
    }
}
