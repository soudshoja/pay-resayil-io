<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp_number',
        'iata_number',
        'address',
        'company_email',
        'phone',
        'logo_path',
        'service_fee_type',
        'service_fee_value',
        'service_fee_payer',
        'whmcs_client_id',
        'subscription_status',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'service_fee_value' => 'decimal:3',
    ];

    /**
     * Get all users belonging to this client
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the admin user for this client
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class)->where('role', 'client_admin');
    }

    /**
     * Get all sales persons for this client
     */
    public function salesPersons(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'sales_person');
    }

    /**
     * Get all accountants for this client
     */
    public function accountants(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'accountant');
    }

    /**
     * Get all agents (travel agencies) for this client
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    /**
     * Get MyFatoorah credentials for this client
     */
    public function myfatoorahCredential(): HasOne
    {
        return $this->hasOne(MyfatoorahCredential::class);
    }

    /**
     * Get all payment requests for this client
     */
    public function paymentRequests(): HasMany
    {
        return $this->hasMany(PaymentRequest::class);
    }

    /**
     * Get all WhatsApp logs for this client
     */
    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class);
    }

    /**
     * Get all activity logs for this client
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get WhatsApp keywords for this client
     */
    public function whatsappKeywords(): HasMany
    {
        return $this->hasMany(WhatsappKeyword::class);
    }

    /**
     * Get webhook configurations for this client
     */
    public function webhookConfigs(): HasMany
    {
        return $this->hasMany(WebhookConfig::class);
    }

    /**
     * Scope for active clients only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if client has valid MyFatoorah credentials
     */
    public function hasValidCredentials(): bool
    {
        return $this->myfatoorahCredential &&
               $this->myfatoorahCredential->is_active &&
               !empty($this->myfatoorahCredential->api_key);
    }

    /**
     * Calculate service fee for a given amount
     */
    public function calculateServiceFee(float $amount): float
    {
        if ($this->service_fee_type === 'percentage') {
            return round($amount * ($this->service_fee_value / 100), 3);
        }
        return (float) $this->service_fee_value;
    }

    /**
     * Get formatted display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }
}
