<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    /**
     * Users assigned to the vehicle.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'assigned_at'])
            ->withTimestamps();
    }
}
