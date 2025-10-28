<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'make',
        'model',
        'year',
        'vin',
        'price',
        'license_plate',
        'type',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'sold_at' => 'datetime',
    ];

    /**
     * Users assigned to the vehicle.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'assigned_at'])
            ->withTimestamps();
    }

    /**
     * Scope vehicles that are still available for purchase.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereNull('sold_at');
    }
}
