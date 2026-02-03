<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'sales_person_id',
        'company_name',
        'iata_number',
        'email',
        'email_verified_at',
        'accountant_whatsapp',
        'address',
        'phone',
        'logo_path',
        'credit_balance',
        'credit_limit',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'credit_balance' => 'decimal:3',
        'credit_limit' => 'decimal:3',
    ];

    /**
     * Get the client this agent belongs to
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the sales person managing this agent
     */
    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    /**
     * Get authorized phone numbers for this agent
     */
    public function authorizedPhones(): HasMany
    {
        return $this->hasMany(AgentAuthorizedPhone::class);
    }

    /**
     * Get payment requests made by this agent
     */
    public function paymentRequests(): HasMany
    {
        return $this->hasMany(PaymentRequest::class);
    }

    /**
     * Get activity logs for this agent
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Scope for active agents only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a phone number is authorized for this agent
     */
    public function isPhoneAuthorized(string $phoneNumber): bool
    {
        $normalized = $this->normalizePhoneNumber($phoneNumber);
        return $this->authorizedPhones()
            ->where('phone_number', $normalized)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Find agent by authorized phone number
     */
    public static function findByPhone(string $phoneNumber): ?self
    {
        $normalized = (new self)->normalizePhoneNumber($phoneNumber);

        $authorizedPhone = AgentAuthorizedPhone::where('phone_number', $normalized)
            ->where('is_active', true)
            ->first();

        return $authorizedPhone?->agent;
    }

    /**
     * Normalize phone number to E.164 format
     */
    private function normalizePhoneNumber(string $phoneNumber): string
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
     * Get display name with IATA
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->iata_number) {
            return "{$this->company_name} (IATA: {$this->iata_number})";
        }
        return $this->company_name;
    }
}
