<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user who performed this action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the agency this log belongs to
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Log an activity
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?array $metadata = null,
        ?Model $model = null
    ): self {
        $user = auth()->user();

        return static::create([
            'user_id' => $user?->id,
            'agency_id' => $user?->client_id ?? $user?->agency_id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
        ]);
    }

    /**
     * Scope by action type
     */
    public function scopeOfAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope by model
     */
    public function scopeForModel($query, Model $model)
    {
        return $query->where('model_type', get_class($model))
                     ->where('model_id', $model->id);
    }

    /**
     * Get formatted action for display
     */
    public function getFormattedActionAttribute(): string
    {
        return match($this->action) {
            'login' => __('messages.activity.login'),
            'logout' => __('messages.activity.logout'),
            'payment_created' => __('messages.activity.payment_created'),
            'payment_paid' => __('messages.activity.payment_paid'),
            'user_created' => __('messages.activity.user_created'),
            'settings_updated' => __('messages.activity.settings_updated'),
            default => $this->action,
        };
    }
}
