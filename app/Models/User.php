<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Available roles in multi-tier structure
     */
    const ROLE_PLATFORM_OWNER = 'platform_owner';
    const ROLE_CLIENT_ADMIN = 'client_admin';
    const ROLE_SALES_PERSON = 'sales_person';
    const ROLE_ACCOUNTANT = 'accountant';

    protected $fillable = [
        'client_id',
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'phone_verified_at',
        'email_verified_at',
        'is_active',
        'is_platform_owner',
        'visible_to_clients',
        'last_login_at',
        'last_login_ip',
        'preferred_locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'is_platform_owner' => 'boolean',
            'visible_to_clients' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is platform owner
     */
    public function isPlatformOwner(): bool
    {
        return $this->is_platform_owner === true || $this->role === self::ROLE_PLATFORM_OWNER;
    }

    /**
     * Check if user is client admin
     */
    public function isClientAdmin(): bool
    {
        return $this->role === self::ROLE_CLIENT_ADMIN;
    }

    /**
     * Check if user is sales person
     */
    public function isSalesPerson(): bool
    {
        return $this->role === self::ROLE_SALES_PERSON;
    }

    /**
     * Check if user is accountant
     */
    public function isAccountant(): bool
    {
        return $this->role === self::ROLE_ACCOUNTANT;
    }

    /**
     * Get the client this user belongs to
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Alias for backward compatibility
     */
    public function agency(): BelongsTo
    {
        return $this->client();
    }

    /**
     * Get agents managed by this sales person
     */
    public function managedAgents(): HasMany
    {
        return $this->hasMany(Agent::class, 'sales_person_id');
    }

    /**
     * Get payment requests created by this user (agent)
     */
    public function paymentRequests(): HasMany
    {
        return $this->hasMany(PaymentRequest::class, 'agent_user_id');
    }

    /**
     * Get WhatsApp logs for this user
     */
    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class);
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get transaction notes created by this user
     */
    public function transactionNotes(): HasMany
    {
        return $this->hasMany(TransactionNote::class, 'created_by_user_id');
    }

    /**
     * Legacy role checks for backward compatibility
     */
    public function isSuperAdmin(): bool
    {
        return $this->isPlatformOwner();
    }

    public function isAdmin(): bool
    {
        return $this->isClientAdmin() || $this->isPlatformOwner();
    }

    public function isAgent(): bool
    {
        return $this->isSalesPerson();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        // Map legacy roles
        $legacyMap = [
            'super_admin' => self::ROLE_PLATFORM_OWNER,
            'admin' => self::ROLE_CLIENT_ADMIN,
            'agent' => self::ROLE_SALES_PERSON,
        ];

        $normalizedRoles = array_map(function ($role) use ($legacyMap) {
            return $legacyMap[$role] ?? $role;
        }, $roles);

        return in_array($this->role, $normalizedRoles);
    }

    /**
     * Scope for active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for client-visible users only
     */
    public function scopeVisibleToClients($query)
    {
        return $query->where('visible_to_clients', true);
    }

    /**
     * Get phone number without + prefix
     */
    public function getPhoneNumberAttribute(): string
    {
        return ltrim($this->username, '+');
    }

    /**
     * Check if phone is verified
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Mark phone as verified
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Update last login info
     */
    public function updateLastLogin(string $ip): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    /**
     * Get role display label
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            self::ROLE_PLATFORM_OWNER => 'Platform Owner',
            self::ROLE_CLIENT_ADMIN => 'Client Admin',
            self::ROLE_SALES_PERSON => 'Sales Person',
            self::ROLE_ACCOUNTANT => 'Accountant',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }
}
