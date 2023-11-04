<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'started_at', 'ended_at'];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
