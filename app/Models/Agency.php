<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agency extends Model
{
    use HasFactory;

    /**
     * The table was renamed from 'agencies' to 'clients' in Phase 2
     */
    protected $table = 'clients';

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

    /**
     * Accessor for backward compatibility: agency_name -> name
     */
    public function getAgencyNameAttribute(): string
    {
        return $this->attributes['name'] ?? '';
    }

    /**
     * Mutator for backward compatibility: agency_name -> name
     */
    public function setAgencyNameAttribute($value): void
    {
        $this->attributes['name'] = $value;
    }

    protected $casts = [
        'is_active' => 'boolean',
        'service_fee_value' => 'decimal:3',
    ];

    /**
     * Get all users belonging to this client (via client_id foreign key)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'client_id');
    }

    /**
     * Get the admin user for this client
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class, 'client_id')->where('role', 'client_admin');
    }

    /**
     * Get all accountants for this client
     */
    public function accountants(): HasMany
    {
        return $this->hasMany(User::class, 'client_id')->where('role', 'accountant');
    }

    /**
     * Get all sales persons for this client
     */
    public function salesPersons(): HasMany
    {
        return $this->hasMany(User::class, 'client_id')->where('role', 'sales_person');
    }

    /**
     * Alias: Get all agents/sales persons for this client
     */
    public function agents(): HasMany
    {
        return $this->salesPersons();
    }

    /**
     * Get MyFatoorah credentials for this client
     */
    public function myfatoorahCredential(): HasOne
    {
        return $this->hasOne(MyfatoorahCredential::class, 'client_id');
    }

    /**
     * Get all payment requests for this client
     */
    public function paymentRequests(): HasMany
    {
        return $this->hasMany(PaymentRequest::class, 'client_id');
    }

    /**
     * Get all WhatsApp logs for this client
     */
    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class, 'client_id');
    }

    /**
     * Get all activity logs for this client
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'client_id');
    }

    /**
     * Get webhook configurations for this client
     */
    public function webhookConfigs(): HasMany
    {
        return $this->hasMany(WebhookConfig::class, 'client_id');
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
