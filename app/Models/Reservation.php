<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['name', 'description', 'started_at', 'ended_at'];

    protected $appends = ['nights'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get reservation nights
     */
    protected function nights(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->started_at->startOfDay()->diffInDays($this->ended_at)
        );
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
